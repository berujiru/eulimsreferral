<?php

namespace frontend\modules\referraladmin\controllers;

use Yii;
use common\models\referral\Methodreference;
use common\models\referral\MethodreferenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MethodreferenceController implements the CRUD actions for Methodreference model.
 */
class MethodreferenceController extends Controller
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
     * Lists all Methodreference models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MethodreferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Methodreference model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Methodreference model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Methodreference();

        if ($model->load(Yii::$app->request->post())) {
            $methodreference = Methodreference::find()->where(['method'=> $model->method, 'reference'=> $model->reference, 'fee'=> $model->fee])->one();
            if ($methodreference){
                Yii::$app->session->setFlash('warning', 'The system has detected a duplicate record. You are not allowed to perform this operation!');
                return $this->runAction('index');
            }else{
                $model->save();
                Yii::$app->session->setFlash('success', 'Method Reference Successfully Created'); 
                return $this->runAction('index');
            }
        } else {
            $model->create_time=date("Y-m-d h:i:s");
            $model->update_time=date("Y-m-d h:i:s");
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Methodreference model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Method Reference Successfully Updated'); 
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Methodreference model.
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
     * Finds the Methodreference model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Methodreference the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Methodreference::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreatemethod()
    {
        $model = new Methodreference();
        $model->testname_id = 0;
        $post= Yii::$app->request->post();
        if ($model->load(Yii::$app->request->post())) {

            $methodreference = Methodreference::find()->where(['method'=> $post['Methodreference']['method'], 'reference'=> $post['Methodreference']['reference'], 'fee'=> $post['Methodreference']['fee']])->one();
            if ($methodreference){
              //  Yii::$app->session->setFlash('warning', "The system has detected a duplicate record. You are not allowed to perform this operation."); 
                return $this->runAction('index');
            }else{
               // Yii::$app->session->setFlash('success', 'Method Reference Successfully Created'); 
                $model->save();
                return $this->runAction('index');
            }
        }
        $model->create_time=date("Y-m-d h:i:s");
        $model->update_time=date("Y-m-d h:i:s");

       $model->testname_id = 0;
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
       }
    }
}
