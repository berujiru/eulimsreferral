<?php

namespace frontend\modules\referraladmin\controllers;

use Yii;
use common\models\referral\Testnamemethod;
use common\models\referral\TestnameMethodSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\referral\Methodreference;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use linslin\yii2\curl;
use common\models\system\Profile;
/**
 * TestnamemethodController implements the CRUD actions for TestnameMethod model.
 */
class TestnamemethodController extends Controller
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
     * Lists all TestnameMethod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TestnameMethodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TestnameMethod model.
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
     * Creates a new TestnameMethod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Testnamemethod();

        if ($model->load(Yii::$app->request->post())) {

            $testnamemethod = Testnamemethod::find()->where(['testname_id'=> $model->testname_id, 'methodreference_id'=>$model->methodreference_id])->one();
            if ($testnamemethod){
                Yii::$app->session->setFlash('warning', "The system has detected a duplicate record. You are not allowed to perform this operation."); 
            }else{
                $profile= Profile::find()->where(['user_id'=> Yii::$app->user->id])->one();
                if($profile){
                $model->added_by=$profile->firstname.' '. strtoupper(substr($profile->middleinitial,0,1)).'. '.$profile->lastname;
                }else{
                    $model->added_by="";
                }
                $model->save();
                Yii::$app->session->setFlash('success', 'Test Name Method Successfully Created'); 
            }
            return $this->runAction('index');   
        }

        if(Yii::$app->request->isAjax){
            $model->create_time=date("Y-m-d h:i:s");
            $model->update_time=date("Y-m-d h:i:s");
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
       }
    }

    /**
     * Updates an existing TestnameMethod model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Successfully Updated'); 
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TestnameMethod model.
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
     * Finds the TestnameMethod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Testnamemethod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Testnamemethod::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetmethod()
	{
      
        $labid = $_GET['lab_id'];
        $testname_id = $_GET['id'];
    
         $methodreference = Methodreference::find()->orderBy(['methodreference_id' => SORT_DESC])->all();
         $testnamedataprovider = new ArrayDataProvider([
                 'allModels' => $methodreference,
                 'pagination' => [
                     'pageSize' =>false,
                 ],
              
         ]);
       

         return $this->renderAjax('_method', [
            'testnamedataprovider' => $testnamedataprovider,    
            'testname_id'=> $testname_id,
         ]);
	
     }
}
