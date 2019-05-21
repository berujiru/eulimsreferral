<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Referral;
use common\models\referral\ReferralSearch;
use common\models\referral\Sample;
use common\models\referral\Analysis;
use common\models\referral\Notification;
use common\models\referral\Customer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\ReferralComponent;
use common\components\ReferralFunctions;
use linslin\yii2\curl;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * ReferralController implements the CRUD actions for Referral model.
 */
class ReferralController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Referral models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReferralSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Referral model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

        if($rstlId > 0)
        {
            $function = new ReferralFunctions();
            $refcomponent = new ReferralComponent();

            $checknotified = $function->checkNotified($id,$rstlId);
            $checkowner = $function->checkowner($id,$rstlId);

            if($checknotified > 0 || $checkowner > 0)
            {
                $model = $this->findModel($id);

                $samples = $model->samples;
                $analyses = Analysis::find()->joinWith('sample',false)->where('referral_id =:referralId',[':referralId'=>$id])->all();
                //$customer = Customer::findOne($model->customer_id);

                //set third parameter to 1 for attachment type deposit slip
                $deposit = json_decode($refcomponent->getAttachment($id,Yii::$app->user->identity->profile->rstl_id,1),true);
                //set third parameter to 2 for attachment type or
                $or = json_decode($refcomponent->getAttachment($id,Yii::$app->user->identity->profile->rstl_id,2),true);

                $sampleDataProvider = new ArrayDataProvider([
                    'allModels' => $samples,
                    'pagination'=> [
                        'pageSize' => 10,
                    ],
                ]);

                $analysisDataprovider = new ArrayDataProvider([
                    'allModels' => $analyses,
                    'pagination'=>false,
                    /*'pagination'=> [
                        'pageSize' => 10,
                    ],*/

                ]);

                $query = new Query;
                $subtotal = $query->from('tbl_analysis')
                   ->join('INNER JOIN', 'tbl_sample', 'tbl_analysis.sample_id = tbl_sample.sample_id')
                   ->where('referral_id =:referralId',[':referralId'=>$id])
                   ->sum('analysis_fee');

                /*$subtotal = Analysis::find()->joinWith('sample',false)
                    ->where('referral_id =:referralId',[':referralId'=>$id])
                    ->sum('analysis_fee');*/

                $rate = $model->discount_rate;
                $discounted = $subtotal * ($rate/100);
                $total = $subtotal - $discounted;

                return $this->render('view', [
                    'model' => $model,
                    //'request' => $request,
                    //'customer' => $customer,
                    'sampleDataProvider' => $sampleDataProvider,
                    'analysisdataprovider'=> $analysisDataprovider,
                    'subtotal' => $subtotal,
                    'discounted' => $discounted,
                    'total' => $total,
                    'countSample' => count($samples),
                    //'notification' => $noticeDetails,
                    'depositslip' => $deposit,
                    'officialreceipt' => $or,
                ]);
            } else {
                Yii::$app->session->setFlash('error', "Your agency doesn't appear notified!");
                return $this->redirect(['/referrals/notification']);
            }
        } else {
            Yii::$app->session->setFlash('error', "Invalid request!");
            return $this->redirect(['/referrals/notification']);
        }

        /*return $this->render('view', [
            'model' => $this->findModel($id),
        ]);*/
    }

    public function actionViewnotice($id)
    {
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        $noticeId = (int) Yii::$app->request->get('notice_id');

        if($rstlId > 0 && $noticeId > 0)
        {
            $function = new ReferralFunctions();
            $refcomponent = new ReferralComponent();

            $checknotified = $function->checkNotified($id,$rstlId);
            $checkowner = $function->checkowner($id,$rstlId);

            $noticeDetails = Notification::find()
                ->where('notification_id =:notificationId AND recipient_id =:recipientId', [':notificationId'=>$noticeId,':recipientId'=>$rstlId])
                ->one();

            if(($checknotified > 0 && count($noticeDetails) > 0) || $checkowner > 0)
            {
                $model = $this->findModel($id);

                $samples = $model->samples;
                $analyses = Analysis::find()->joinWith('sample',false)->where('referral_id =:referralId',[':referralId'=>$id])->all();

                //set third parameter to 1 for attachment type deposit slip
                $deposit = json_decode($refcomponent->getAttachment($id,Yii::$app->user->identity->profile->rstl_id,1),true);
                //set third parameter to 2 for attachment type or
                $or = json_decode($refcomponent->getAttachment($id,Yii::$app->user->identity->profile->rstl_id,2),true);

                $sampleDataProvider = new ArrayDataProvider([
                    'allModels' => $samples,
                    'pagination'=> [
                        'pageSize' => 10,
                    ],
                ]);

                $analysisDataprovider = new ArrayDataProvider([
                    'allModels' => $analyses,
                    //'pagination'=>false,
                    'pagination'=> [
                        'pageSize' => 10,
                    ],

                ]);

                $query = new Query;
                $subtotal = $query->from('tbl_analysis')
                   ->join('INNER JOIN', 'tbl_sample', 'tbl_analysis.sample_id = tbl_sample.sample_id')
                   ->where('referral_id =:referralId',[':referralId'=>$id])
                   ->sum('analysis_fee');

                $rate = $model->discount_rate;
                $discounted = $subtotal * ($rate/100);
                $total = $subtotal - $discounted;

                return $this->render('view', [
                    'model' => $model,
                    'sampleDataProvider' => $sampleDataProvider,
                    'analysisdataprovider'=> $analysisDataprovider,
                    'subtotal' => $subtotal,
                    'discounted' => $discounted,
                    'total' => $total,
                    'countSample' => count($samples),
                    'notification' => $noticeDetails,
                    'depositslip' => $deposit,
                    'officialreceipt' => $or,
                ]);
            } else {
                Yii::$app->session->setFlash('error', "Your agency doesn't appear notified!");
                return $this->redirect(['/referrals/notification']);
            }
        } else {
            Yii::$app->session->setFlash('error', "Invalid request!");
            return $this->redirect(['/referrals/notification']);
        }
    }

    /**
     * Creates a new Referral model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Referral();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->referral_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Referral model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->referral_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Referral model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Referral model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Referral the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Referral::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
