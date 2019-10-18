<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Referraltrackreceiving;
use common\models\referral\ReferraltrackreceivingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use common\models\referral\Referral;
use frontend\modules\referrals\controllers\ReferralController;

/**
 * ReferraltrackreceivingController implements the CRUD actions for Referraltrackreceiving model.
 */
class ReferraltrackreceivingController extends Controller
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
     * Lists all Referraltrackreceiving models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReferraltrackreceivingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Referraltrackreceiving model.
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
     * Creates a new Referraltrackreceiving model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate($referralid,$testingid,$receiveddate)
    public function actionCreate($referralid)
    {
        
        $model = new Referraltrackreceiving();
        
        if ($model->load(Yii::$app->request->post())) {
            
            $model->referral_id=$referralid;
            $model->date_created=date('Y-m-d H:i:s');
            $model->testing_agency_id=$model->referral->testing_agency_id;
            $model->receiving_agency_id=Yii::$app->user->identity->profile->rstl_id;
            $model->sample_received_date=$model->referral->sample_received_date;
            $model->save();
            $test=ReferralController::Checkstatuslogs($referralid, 2);
            if($test == 0){
                $status=ReferralController::Statuslogs($referralid,2); 
            }
            if($status>0){
                 Yii::$app->session->setFlash('success', 'Successfully Created!');
                return $this->redirect(['/referrals/referral/view', 'id' => $referralid]);  
            }   
        }

        
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Referraltrackreceiving model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->referraltrackreceiving_id]);
            Yii::$app->session->setFlash('success', 'Successfully Updated!');
            return $this->redirect(['/referrals/referral/view', 'id' => $model->referral_id]); 
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Referraltrackreceiving model.
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
     * Finds the Referraltrackreceiving model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Referraltrackreceiving the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Referraltrackreceiving::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
