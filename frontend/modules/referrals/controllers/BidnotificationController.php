<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Bidnotification;
use common\models\referral\BidnotificationSearch;
use common\models\referral\Referral;
use common\models\referral\Agency;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\components\ReferralFunctions;
use yii\data\ArrayDataProvider;

/**
 * BidnotificationController implements the CRUD actions for Bidnotification model.
 */
class BidnotificationController extends Controller
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
     * Lists all Bidnotification models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(isset(Yii::$app->user->identity->profile->rstl_id)){
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
            $function= new ReferralFunctions();
            $query = Bidnotification::find()->where('recipient_agency_id =:recipientAgencyId', [':recipientAgencyId'=>$rstlId]);
            $bidnotification = $query->orderBy('posted_at DESC')->all();
            $count = $query->count();

        } else {
            //return 'Session time out!';
            return $this->redirect(['/site/login']);
        }

        $list = [];
        if($count > 0){
            foreach ($bidnotification as $data) {
                $bid_notification_type = $data->bid_notification_type_id;
                switch($data->bid_notification_type_id){
                    case 1:
                        $agencyName = $this->getAgency($data->postedby_agency_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$agencyName."</b> notified referral request for bidding.",'notice_id'=>$data->bid_notification_id,'notification_date'=>$data->posted_at,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id,'seen'=>$data->seen];
                    break;
                    case 2:
                        $agencyName = $this->getAgency($data->postedby_agency_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$agencyName."</b> placed bids to your referral request.",'notice_id'=>$data->bid_notification_id,'notification_date'=>$data->posted_at,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id,'seen'=>$data->seen];
                    break;
                }
                array_push($list, $arr_data);
            }
        } else {
            $list = [];
        }

        $notificationDataProvider = new ArrayDataProvider([
            //'key'=>'notification_id',
            //'allModels' => $notification['notification'],
            'allModels' => $list,
            'pagination' => [
                'pageSize' => 10,
            ],
            //'pagination'=>false,
        ]);


        if(\Yii::$app->request->isAjax){
            return $this->renderAjax('bidnotifications_all', [
                'notifications' => $list,
                'count_notice' => $count,
                'notificationProvider' => $notificationDataProvider,
            ]);
        } else {
            return $this->render('bidnotifications_all', [
                'notifications' => $list,
                'count_notice' => $count,
                'notificationProvider' => $notificationDataProvider,
            ]);
        }
    }

    /**
     * Displays a single Bidnotification model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bidnotification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bidnotification();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bid_notification_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Bidnotification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bid_notification_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Bidnotification model.
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
     * Finds the Bidnotification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bidnotification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bidnotification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //send notification
    public function actionNotify()
    {
        if(Yii::$app->request->get('referral_id')){
            $referralId = (int) Yii::$app->request->get('referral_id');
            $referral = $this->findReferral($referralId);
        } else {
            Yii::$app->session->setFlash('error', "Referral ID not valid!");
            return $this->redirect(['/referrals/bidnotification']);
        }

        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

        if($rstlId > 0){
            return "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:18px;'></span>&nbsp;No agency to be notified!</div>";
        } else {
            return "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:18px;'></span>&nbsp;No agency to be notified!</div>";
        }
    }

    //get unseen bid notifications
    public function actionCount_unseen_bidnotification()
    {   
        if(isset(Yii::$app->user->identity->profile->rstl_id)){
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

            $function= new ReferralFunctions();
            $count_all_notifications = $function->countAllNotification($rstlId);

            $bidNotificationCount = Bidnotification::find()
                ->where('recipient_agency_id =:recipientAgencyId', [':recipientAgencyId'=>$rstlId])
                ->andWhere('seen =:seen',[':seen'=>0])
                ->count();

            return Json::encode(['bid_notification'=>$bidNotificationCount,'all_notifications'=>$count_all_notifications]);
        } else {
            //return 'Session time out!';
            return $this->redirect(['/site/login']);
        }
    }

    //get list of unresponded notifications
    public function actionList_unseen_bidnotification()
    {
        if(isset(Yii::$app->user->identity->profile->rstl_id)){
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
            $function= new ReferralFunctions();
            $query = Bidnotification::find()->where('recipient_agency_id =:recipientAgencyId AND seen =:seen', [':recipientAgencyId'=>$rstlId,':seen'=>0]);
            $bidnotification = $query->limit(10)->orderBy('posted_at DESC')->all();
            $count = $query->count();
        } else {
            //return 'Session time out!';
            return $this->redirect(['/site/login']);
        }

        $notice_list = [];
        if(count($count) > 0) {
            foreach ($bidnotification as $data) {
                //$bid_notification_type = $data->bid_notification_type_id;
                switch($data['bid_notification_type_id']){
                    case 1:
                        $agencyName = $this->getAgency($data->postedby_agency_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$agencyName."</b> notified referral request for bidding.",'notice_id'=>$data->bid_notification_id,'notification_date'=>$data->posted_at,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id];
                    break;
                    case 2:
                        $agencyName = $this->getAgency($data->postedby_agency_id);
                        $checkOwner = $function->checkowner($data->referral_id,$rstlId);
                        $arr_data = ['notice_sent'=>"<b>".$agencyName."</b> placed bids to your referral request.",'notice_id'=>$data->bid_notification_id,'notification_date'=>$data->posted_at,'referral_id'=>$data->referral_id,'owner'=>$checkOwner,'local_request_id'=>$data->referral->local_request_id];
                    break;
                }
                array_push($notice_list, $arr_data);
            }
        } else {
            $notice_list = [];
        }

        if(\Yii::$app->request->isAjax){
            return $this->renderAjax('list_unseen_bidnotification', [
                //'notifications' => $unseen_notification,
                'notifications' => $notice_list,
            ]);
        }
    }

    //find referral request
    protected function findReferral($id)
    {
        $model = Referral::find()->where(['referral_id'=>$id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested Request its either does not exist or you have no permission to view it.');
        }
    }

    //get list agencies
    private function getAgency($agencyId)
    {
        $agency = Agency::findOne($agencyId);

        if($agency !== null){
            return $agency->name;
        } else {
            return null;
        }
    }

    //get referral code
    private function getReferral($referralId)
    {
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        $referral = Referral::findOne($referralId);

        if($referral !==  null){
            return $referral;
        } else {
            return null;
        }
    }
}
