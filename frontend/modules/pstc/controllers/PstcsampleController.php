<?php

namespace frontend\modules\pstc\controllers;

use Yii;
use common\models\referral\Pstcsample;
use common\models\referral\PstcsampleSearch;
use common\models\referral\Pstcanalysis;
use common\models\referral\Pstcrequest;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\referral\Samplename;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * PstcsampleController implements the CRUD actions for Pstcsample model.
 */
class PstcsampleController extends Controller
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
     * Lists all Pstcsample models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PstcsampleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pstcsample model.
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
     * Creates a new Pstcsample model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pstcsample();

        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pstc_sample_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]); */
        if(Yii::$app->request->post()) {

            $connection= Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
            if($rstlId > 0){
                $requestId = (int) Yii::$app->request->get('request_id');
                $post = Yii::$app->request->post('Pstcsample');

                if(Yii::$app->request->post('qnty')) {
                    $quantity = (int) Yii::$app->request->post('qnty');
                } else {
                    $quantity = 1;
                }

                /*$updateRequest = 0;
                if(Yii::$app->request->post('checked') == 1) {
                    $for_referral = (int) Yii::$app->request->post('checked');
                    $pstc_request = Pstcrequest::findOne($requestId);
                    $pstc_request->is_referral = 1;
                    if($pstc_request->save(false)) {

                        $query = "UPDATE `tbl_pstcrequest` r
                            LEFT JOIN `tbl_pstcsample` s ON r.`pstc_request_id` = s.`pstc_request_id`
                            LEFT JOIN `tbl_pstcanalysis` a ON s.`pstc_sample_id` = a.`pstc_sample_id`
                            SET s.`is_referral` = 1, a.`analysis_offered` = 0
                            WHERE r.`pstc_request_id`=:requestId";

                        $params = [':requestId' => $requestId];
                        $command = $connection->createCommand($query);
                        $command->bindValues($params);
                        $command->execute();

                        $updateRequest = 1;
                    } else {
                        $updateRequest = 0;
                        $transaction->rollBack();
                    }
                } */
                $model_request = Pstcrequest::findOne($requestId);

                if($quantity > 1) {
                    $sampleSave = 0;
                    for ($i=1;$i<=$quantity;$i++)
                    {
                        $sample = new Pstcsample();

                        if($model_request->is_referral == 1) {
                            $sample->is_referral = 1;
                            //$sample->sample_received_date = date('Y-m-d H:i:s', strtotime($post['sample_received_date']));
                        }

                        $sample->rstl_id = $rstlId;
                        $sample->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                        $sample->pstc_request_id = $requestId;
                        $sample->sampling_date = empty($post['sampling_date']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($post['sampling_date']));
                        $sample->sample_name = $post['sample_name'];
                        $sample->sample_description = $post['sample_description'];
                        $sample->created_at = date('Y-m-d H:i:s');
                        $sample->updated_at = date('Y-m-d H:i:s');

                        if($sample->save()) {
                            $sampleSave = 1;
                        } else {
                            $sampleSave = 0;
                            $transaction->rollBack();
                        }
                    }
                    if($sampleSave == 1) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Sample successfully created!');
                        return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                        //return $this->redirect(['/pstc/pstcrequest']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Failed to add sample!');
                        return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                    }
                } else {

                    if($model_request->is_referral == 1) {
                        $model->is_referral = 1;
                        //$sample->sample_received_date = date('Y-m-d H:i:s', strtotime($post['sample_received_date']));
                    }

                    $model->rstl_id = $rstlId;
                    $model->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                    $model->pstc_request_id = $requestId;
                    $model->sampling_date = empty($post['sampling_date']) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($post['sampling_date']));
                    $model->sample_name = $post['sample_name'];
                    $model->sample_description = $post['sample_description'];
                    $model->created_at = date('Y-m-d H:i:s');
                    $model->updated_at = date('Y-m-d H:i:s');

                    if($model->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Sample successfully created!');
                        return $this->redirect(['/pstc/pstcrequest/view', 'id' => $model->pstc_request_id]);
                        //return $this->redirect(['/pstc/pstcrequest']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Failed to add sample!');
                        return $this->redirect(['/pstc/pstcrequest/view', 'id' => $model->pstc_request_id]);
                    }
                }
            } else {
                return $this->redirect(['/site/login']);
            }
        } else {

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'sampletemplate' => $this->listSampletemplate(),
                ]);
            } else {
                /*return $this->render('create', [
                    'model' => $model,
                    'sampletemplate' => $this->listSampletemplate(),
                ]);*/
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'sampletemplate' => $this->listSampletemplate(),
                ]);
            }
        }
    }

    /**
     * Updates an existing Pstcsample model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        /*$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pstc_sample_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]); */
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
            if($rstlId > 0){
                $requestId = (int) Yii::$app->request->get('request_id');
                if($requestId > 0){
                    $post = Yii::$app->request->post('Pstcsample');

                    /*$updateRequest = 0;
                    if(Yii::$app->request->post('checked') == 1) {
                        $for_referral = (int) Yii::$app->request->post('checked');
                        $pstc_request = Pstcrequest::findOne($requestId);
                        $pstc_request->is_referral = 1;
                        if($pstc_request->save(false)) {

                            $query = "UPDATE `tbl_pstcrequest` r
                                LEFT JOIN `tbl_pstcsample` s ON r.`pstc_request_id` = s.`pstc_request_id`
                                LEFT JOIN `tbl_pstcanalysis` a ON s.`pstc_sample_id` = a.`pstc_sample_id`
                                SET s.`is_referral` = 1, a.`analysis_offered` = 0
                                WHERE r.`pstc_request_id`=:requestId";

                            $params = [':requestId' => $requestId];
                            $command = $connection->createCommand($query);
                            $command->bindValues($params);
                            $command->execute();

                            $updateRequest = 1;
                        } else {
                            $updateRequest = 0;
                            $transaction->rollBack();
                        }
                    } */
                    $model_request = Pstcrequest::findOne($requestId);

                    if(!empty($post['sampling_date'])) {
                        $model->sampling_date = date('Y-m-d H:i:s', strtotime($post['sampling_date']));
                    }

                    if($model_request->is_referral == 1) {
                        $model->is_referral = 1;
                    }

                    $model->sample_name = $post['sample_name'];
                    $model->sample_description = $post['sample_description'];
                    $model->updated_at = date('Y-m-d H:i:s');

                    if($model->save(false)){
                        Yii::$app->session->setFlash('success', 'Sample successfully updated!');
                        return $this->redirect(['/pstc/pstcrequest/view', 'id' => $model->pstc_request_id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'Failed to update sample!');
                        return $this->redirect(['/pstc/pstcrequest/view', 'id' => $model->pstc_request_id]);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Request not valid!');
                    return $this->redirect(['/pstc/pstcrequest']);
                }
            } else {
                return $this->redirect(['/site/login']);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'sampletemplate' => $this->listSampletemplate(),
                ]);
            } else {
                /* return $this->render('update', [
                    'model' => $model,
                    'sampletemplate' => $this->listSampletemplate(),
                ]); */
                 return $this->renderAjax('_form', [
                    'model' => $model,
                    'sampletemplate' => $this->listSampletemplate(),
                ]);
            }   
        }
    }

    /**
     * Deletes an existing Pstcsample model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        //return $this->redirect(['index']);

        $model = $this->findModel($id);
        $analyses = Pstcanalysis::find()->where(['pstc_sample_id' => $id])->all();

        if(count($analyses) > 0){
            Yii::$app->session->setFlash('error', $model->sample_name." has analysis.\nRemove first the analysis then delete this sample.");
            return $this->redirect(['/pstc/pstcrequest/view', 'id' => $model->pstc_request_id]);
        } else {
            if($model->delete()){
                Yii::$app->session->setFlash('success', 'Successfully removed sample!');
                return $this->redirect(['/pstc/pstcrequest/view', 'id' => $model->pstc_request_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to remove sample!');
                return $this->redirect(['/pstc/pstcrequest/view', 'id' => $model->pstc_request_id]);
            }
        }
    }

    /**
     * Finds the Pstcsample model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pstcsample the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pstcsample::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetlist_template() {
        if(isset($_GET['template_id'])){
            $id = (int) $_GET['template_id'];
            $modelSampletemplate =  Samplename::findOne(['sample_name_id'=>$id]);
            if(count($modelSampletemplate)>0){
                $sampleName = $modelSampletemplate->sample_name;
                $sampleDescription = $modelSampletemplate->description;
            } else {
                $sampleName = "";
                $sampleDescription = "";
            }
        } else {
            $sampleName = "Error getting sample name";
            $sampleDescription = "Error getting description";
        }
        return Json::encode([
            'name'=>$sampleName,
            'description'=>$sampleDescription,
        ]);
    }

    protected function listSampletemplate()
    {
        $sampleTemplate = ArrayHelper::map(Samplename::find()->all(), 'sample_name_id', 
            function($sampleTemplate, $defaultValue) {
                return $sampleTemplate->sample_name;
        });

        return $sampleTemplate;
    }
}
