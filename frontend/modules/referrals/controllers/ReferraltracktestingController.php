<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Referraltracktesting;
use common\models\referral\ReferraltracktestingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\modules\referrals\controllers\ReferralController;

/**
 * ReferraltracktestingController implements the CRUD actions for Referraltracktesting model.
 */
class ReferraltracktestingController extends Controller
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
     * Lists all Referraltracktesting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReferraltracktestingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Referraltracktesting model.
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
     * Creates a new Referraltracktesting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($referralid,$receivingid)
    {
        $model = new Referraltracktesting();

        if ($model->load(Yii::$app->request->post())) {
            $model->testing_agency_id=Yii::$app->user->identity->profile->rstl_id;
            $model->referral_id=$referralid;
            $model->date_created=date('Y-m-d H:i:s');
            $model->receiving_agency_id=$receivingid;
            $model->save();
            $status=ReferralController::Statuslogs($referralid,3); // #3 means Accepted
            
            if($model->analysis_started <> ""){
                $test=ReferralController::Checkstatuslogs($refid, 4);
                if($test == 0){
                    $analysisstarted=ReferralController::Statuslogs($refid,4); 
                }
            }
            if($model->analysis_completed <> ""){
                $test=ReferralController::Checkstatuslogs($refid, 5);
                if($test == 0){
                    $analysiscompleted=ReferralController::Statuslogs($refid,5); 
                }
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
     * Updates an existing Referraltracktesting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $refid=$model->referral_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->analysis_started <> ""){
                $test=ReferralController::Checkstatuslogs($refid, 4);
                if($test == 0){
                    $analysisstarted=ReferralController::Statuslogs($refid,4); 
                }
            }
            if($model->analysis_completed <> ""){
                $test=ReferralController::Checkstatuslogs($refid, 5);
                if($test == 0){
                    $analysiscompleted=ReferralController::Statuslogs($refid,5); 
                }
            }
            Yii::$app->session->setFlash('success', 'Successfully Updated!');
            return $this->redirect(['/referrals/referral/view', 'id' => $refid]); 
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Referraltracktesting model.
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
     * Finds the Referraltracktesting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Referraltracktesting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Referraltracktesting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
