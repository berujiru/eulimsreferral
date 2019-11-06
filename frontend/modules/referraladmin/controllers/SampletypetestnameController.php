<?php

namespace frontend\modules\referraladmin\controllers;

use Yii;
use common\models\referral\Sampletypetestname;
use common\models\referral\SampletypetestnameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\referral\Sampletype;
use common\models\referral\Testname;
use common\models\system\Profile;

/**
 * SampletypetestnameController implements the CRUD actions for Sampletypetestname model.
 */
class SampletypetestnameController extends Controller
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
     * Lists all Sampletypetestname models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SampletypetestnameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sampletypetestname model.
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
     * Creates a new Sampletypetestname model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sampletypetestname();
        $sampletypelist= ArrayHelper::map(Sampletype::find()->orderBy(['sampletype_id' => SORT_DESC])->all(),'sampletype_id','type');
        $testnamelist= ArrayHelper::map(Testname::find()->orderBy(['testname_id' => SORT_DESC])->all(),'testname_id','test_name');
        if ($model->load(Yii::$app->request->post())) {
            $sampletypetestname = Sampletypetestname::find()->where(['sampletype_id'=> $model->sampletype_id, 'testname_id'=>$model->testname_id])->one();
            if ($sampletypetestname){
                Yii::$app->session->setFlash('warning', "The system has detected a duplicate record. You are not allowed to perform this operation."); 
            }else{
                $model->date_added= date('Y-m-d');
                $model->save();
                Yii::$app->session->setFlash('success', 'Sample Type Test Name Successfully Created'); 
                
            }      
            return $this->runAction('index');
        } else {
            $profile= Profile::find()->where(['user_id'=> Yii::$app->user->id])->one();
            if($profile){
            $model->added_by=$profile->firstname.' '. strtoupper(substr($profile->middleinitial,0,1)).'. '.$profile->lastname;
            }else{
                $model->added_by="";
            }
            return $this->renderAjax('create', [
                'model' => $model,
                'sampletypelist' => $sampletypelist,
                'testnamelist' => $testnamelist
            ]);
        }
    }

    /**
     * Updates an existing Sampletypetestname model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $sampletypelist= ArrayHelper::map(Sampletype::find()->orderBy(['sampletype_id' => SORT_DESC])->all(),'sampletype_id','type');
        $testnamelist= ArrayHelper::map(Testname::find()->orderBy(['testname_id' => SORT_DESC])->all(),'testname_id','test_name');
       
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Successfully Updated'); 
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
                'sampletypelist' => $sampletypelist,
                'testnamelist' => $testnamelist
            ]);
        }
    }

    /**
     * Deletes an existing Sampletypetestname model.
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
     * Finds the Sampletypetestname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sampletypetestname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sampletypetestname::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
