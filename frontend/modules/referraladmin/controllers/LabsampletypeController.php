<?php

namespace frontend\modules\referraladmin\controllers;

use Yii;
use common\models\referral\LabSampletype;
use common\models\referral\LabSampletypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\referral\Lab;
use common\models\referral\Sampletype;
use common\models\system\Profile;
/**
 * LabsampletypeController implements the CRUD actions for LabSampletype model.
 */
class LabsampletypeController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all LabSampletype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LabSampletypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LabSampletype model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new LabSampletype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Labsampletype();
        $lablist= ArrayHelper::map(Lab::find()->all(),'lab_id','labname');
        $sampletypelist= ArrayHelper::map(Sampletype::find()->orderBy(['sampletype_id' => SORT_DESC])->all(),'sampletype_id','type');

        if ($model->load(Yii::$app->request->post())) {
            $labsampletype = Labsampletype::find()->where(['lab_id'=> $model->lab_id, 'sampletype_id'=>$model->sampletype_id])->one();

            if ($labsampletype){
               Yii::$app->session->setFlash('warning', 'The system has detected a duplicate record. You are not allowed to perform this operation!');
               return $this->redirect(['/referraladmin/labsampletype']);
            }else{
                $model->date_added=date("Y-m-d");
                $model->save();
                Yii::$app->session->setFlash('success', 'Lab Sample Type Successfully Created'); 
                return $this->redirect(['/referraladmin/labsampletype']);
            }
            
        } else {
            $profile= Profile::find()->where(['user_id'=> Yii::$app->user->id])->one();
            if($profile){
            $model->added_by=$profile->firstname.' '. strtoupper(substr($profile->middleinitial,0,1)).'. '.$profile->lastname;
            }else{
                $model->added_by="";
            }
            return $this->renderAjax('create', [
                'model' => $model,
                'lablist' => $lablist,
                'sampletypelist' => $sampletypelist
            ]);
        }
    }

    /**
     * Updates an existing LabSampletype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lablist= ArrayHelper::map(Lab::find()->all(),'lab_id','labname');
        $sampletypelist= ArrayHelper::map(Sampletype::find()->orderBy(['sampletype_id' => SORT_DESC])->all(),'sampletype_id','type');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Lab Sample Type Successfully Updated'); 
            return $this->redirect(['/referraladmin/labsampletype']);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
                'lablist' => $lablist,
                'sampletypelist' => $sampletypelist
            ]);
        }
    }

    /**
     * Deletes an existing LabSampletype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Successfully Deleted'); 
        return $this->redirect(['index']);
    }

    /**
     * Finds the LabSampletype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LabSampletype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Labsampletype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
