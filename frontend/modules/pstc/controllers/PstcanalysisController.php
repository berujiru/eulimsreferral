<?php

namespace frontend\modules\pstc\controllers;

use Yii;
use common\models\referral\Pstcanalysis;
use common\models\referral\PstcanalysisSearch;
use common\models\referral\Pstcsample;
use common\models\referral\Pstcrequest;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\models\lab\Testcategory;
use common\models\lab\Sampletype;
use common\models\lab\Testname;
use common\models\lab\Testnamemethod;
use common\models\lab\Methodreference;
use common\models\lab\Packagelist;

/**
 * PstcanalysisController implements the CRUD actions for Pstcanalysis model.
 */
class PstcanalysisController extends Controller
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
     * Lists all Pstcanalysis models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PstcanalysisSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pstcanalysis model.
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
     * Creates a new Pstcanalysis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pstcanalysis();

        /* if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pstc_analysis_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]); */
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        if($rstlId > 0) {
            $requestId = (int) Yii::$app->request->get('request_id');
            $testnameId = (int) Yii::$app->request->get('testname_id');
            $sampleQuery = Pstcsample::find()->where('pstc_request_id = :requestId', [':requestId' => $requestId]);
    
            $sampleDataProvider = new ActiveDataProvider([
                'query' => $sampleQuery,
                'pagination' => false,
            ]);

            if($testnameId > 0) {
                $methods = $this->listMethodreference($testnameId);
            } else {
                $methods = [];
            }

            $methodrefDataProvider = new ArrayDataProvider([
                //'key'=>'testname_method_id',
                'allModels' => $methods,
                'pagination' => [
                    'pageSize' => 10,
                ],
                //'pagination'=>false,
            ]);

            if(Yii::$app->request->post()) {

                $model_request = Pstcrequest::findOne($requestId);

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $postData = Yii::$app->request->post();
                $pstcData = Yii::$app->request->post('Pstcanalysis');

                //foreach ($postData as $key => $value) {
                    # code...
                //}

                $analysisSave = 0;
                $saveSampleUpdate = 1;
                foreach ($postData['sample_ids'] as $sample) {

                    $sampleUpdate = Pstcsample::findOne($sample);
                    $tc = 0;
                    $st = 0;
                    if(empty($sampleUpdate->testcategory_id)) {
                        $sampleUpdate->testcategory_id = !empty($pstcData['testcategory_id']) ? (int) $pstcData['testcategory_id'] : NULL;
                        $tc = 1;
                    }

                    if(empty($sampleUpdate->sampletype_id)) {
                        $sampleUpdate->sampletype_id = !empty($pstcData['sampletype_id']) ? (int) $pstcData['sampletype_id'] : NULL;
                        $st = 1;
                    }

                    if($tc == 1 || $st == 1){
                        if($sampleUpdate->save(false)) {
                            $saveSampleUpdate = 1;
                        } else {
                            $transaction->rollBack();
                            $saveSampleUpdate = 0;
                        }
                    }

                    $testnameId = (int) $pstcData['testname_id'];
                    $methodrefId = (int) $postData['method_id'];
                    $test = $this->getTestnameOne($testnameId);
                    $method = $this->getMethodrefOne($methodrefId);

                    $analysis = new Pstcanalysis();

                    if($model_request->is_referral == 1) {
                        $analysis->analysis_offered = 0;
                    }

                    $analysis->pstc_sample_id = (int) $sample;
                    $analysis->rstl_id = (int) $rstlId;
                    $analysis->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                    $analysis->testname_id = (int) $testnameId;
                    $analysis->testname = $test->testName;
                    $analysis->method_id = (int) $methodrefId;
                    $analysis->method = $method->method;
                    $analysis->reference = $method->reference;
                    $analysis->fee =$method->fee;
                    $analysis->quantity = 1; 
                    //$analysis->test_id = (int) $testId;
                    $analysis->testcategory_id = (int) $pstcData['testcategory_id'];
                    $analysis->sampletype_id = (int) $pstcData['sampletype_id'];
                    $analysis->created_at = date('Y-m-d H:i:s');
                    $analysis->updated_at = date('Y-m-d H:i:s');

                    if(!$analysis->save(false)){
                        goto analysisfail;
                    } else {
                        $analysisSave = 1;
                    }
                }

                //$discount = $component->getDiscountOne($request->discount_id);
                //$rate = $discount->rate;
                //$fee = $connection->createCommand('SELECT SUM(fee) as total FROM tbl_pstcanalysis WHERE request_id =:requestId')
                //    ->bindValue(':requestId',$requestId)->queryOne();
                //$subtotal = $fee['subtotal'];
                //$total = $subtotal - ($subtotal * ($rate/100));

                //$request->total = $fee['total'];
                //$request->save();
                if($analysisSave == 1 && $saveSampleUpdate == 1){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Analysis successfully saved.");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                } else {
                    goto analysisfail;
                }
                analysisfail: {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Analysis failed to save!");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                }
            }
        } else {
            return $this->redirect(['/site/login']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampletype' => $this->listSampletype(),
                'testname' => $this->listTestname(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]);
        } else {
            /*return $this->render('create', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]); */
            return $this->renderAjax('_form', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampletype' => $this->listSampletype(),
                'testname' => $this->listTestname(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]);
        }
    }

    /**
     * Updates an existing Pstcanalysis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->pstc_analysis_id]);
        // }

        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

        if($rstlId > 0) {
            $requestId = (int) Yii::$app->request->get('request_id');
            $testnameId = (int) Yii::$app->request->get('testname_id');
            $sampleQuery = Pstcsample::find()->where('pstc_request_id = :requestId', [':requestId' => $requestId]);
    
            $sampleDataProvider = new ActiveDataProvider([
                'query' => $sampleQuery,
                'pagination' => false,
            ]);

            if($testnameId > 0) {
                $methods = $this->listMethodreference($testnameId);
            } else {
                $methods = $this->listMethodreference($model->testname_id);
            }

            $methodrefDataProvider = new ArrayDataProvider([
                //'key'=>'testname_method_id',
                'allModels' => $methods,
                'pagination' => [
                    'pageSize' => 10,
                ],
                //'pagination'=>false,
            ]);

            if(Yii::$app->request->post()) {

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $postData = Yii::$app->request->post();

                $sampleId = (int) $postData['sample_id'];
                $sampleUpdate = Pstcsample::findOne($sampleId);
                $tc = 0;
                $st = 0;
                if(empty($sampleUpdate->testcategory_id)) {
                    $sampleUpdate->testcategory_id = !empty($pstcData['testcategory_id']) ? (int) $pstcData['testcategory_id'] : NULL;
                    $tc = 1;
                }

                if(empty($sampleUpdate->sampletype_id)) {
                    $sampleUpdate->sampletype_id = !empty($pstcData['sampletype_id']) ? (int) $pstcData['sampletype_id'] : NULL;
                    $st = 1;
                }

                $saveSampleUpdate = 1;
                if($tc == 1 || $st == 1) {
                    if($sampleUpdate->save(false)) {
                        $saveSampleUpdate = 1;
                    } else {
                        $transaction->rollBack();
                        $saveSampleUpdate = 0;
                    }
                }

                $analysisSave = 0;
                $testnameId = (int) $postData['Pstcanalysis']['testname_id'];
                $methodrefId = (int) $postData['method_id'];
                $test = $this->getTestnameOne($testnameId);
                $method = $this->getMethodrefOne($methodrefId);

                //$model = new Pstcanalysis();
                if($model_request->is_referral == 1) {
                    $analysis->analysis_offered = 0;
                }

                $model->pstc_sample_id = $sampleId;
                $model->rstl_id = (int) $rstlId;
                $model->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                $model->testname_id = (int) $testnameId;
                $model->testname = $test->testName;
                $model->method_id = (int) $methodrefId;
                $model->method = $method->method;
                $model->reference = $method->reference;
                $model->fee =$method->fee;
                $model->quantity = 1; 
                //$model->test_id = (int) $testId;
                $model->testcategory_id = (int) $postData['Pstcanalysis']['testcategory_id'];
                $model->sampletype_id = (int) $postData['Pstcanalysis']['sampletype_id'];
                //$model->created_at = date('Y-m-d H:i:s');
                $model->updated_at = date('Y-m-d H:i:s');

                if(!$model->save(false)){
                    goto analysisfail;
                } else {
                    $analysisSave = 1;
                }

                if($analysisSave == 1 && $saveSampleUpdate == 1){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Analysis successfully updated.");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                } else {
                    goto analysisfail;
                }

                analysisfail: {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Analysis failed to update!");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                }
            }
        } else {
            return $this->redirect(['/site/login']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampletype' => $this->listSampletype(),
                'testname' => $this->listTestname(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]);
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampletype' => $this->listSampletype(),
                'testname' => $this->listTestname(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Pstcanalysis model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        // return $this->redirect(['index']);

        $model = $this->findModel($id);
        $requestId = (int) Yii::$app->request->get('request_id');
        $countAnalysis = Pstcanalysis::find()->where('pstc_sample_id =:sampleId',[':sampleId'=>$model->pstc_sample_id])->count();

        $updateSample = 1;
        if($countAnalysis == 1) {
            $sample = Pstcsample::findOne($model->pstc_sample_id);
            $sample->testcategory_id = '';
            $sample->sampletype_id = '';
            if($sample->save(false)){
                $updateSample = 1;
            } else {
                $transaction->rollBack();
                $updateSample = 0;
            }
        }

        if($model->delete() && $requestId > 0 && $updateSample == 1) {
            Yii::$app->session->setFlash('success', 'Successfully removed analysis.');
            return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
        } else {
            Yii::$app->session->setFlash('error', 'Failed to remove analysis!');
            return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
        }
    }

    /**
     * Finds the Pstcanalysis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pstcanalysis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pstcanalysis::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*******for pacakage*******/

    public function actionPackage()
    {
        $model = new Pstcanalysis();

        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        if($rstlId > 0) {
            $requestId = (int) Yii::$app->request->get('request_id');
            //$testcategoryId = (int) Yii::$app->request->get('testcategory_id');
            //$sampletypeId = (int) Yii::$app->request->get('sampletype_id');
            $sampleQuery = Pstcsample::find()->where('pstc_request_id = :requestId', [':requestId' => $requestId]);

            //if($testcategoryId > 0 && $sampletypeId > 0) {
            //    $listpackage = $this->listPackage($testcategoryId,$sampletypeId);
            //} else {
            $listpackage = 0;
            //}
    
            $sampleDataProvider = new ActiveDataProvider([
                'query' => $sampleQuery,
                'pagination' => false,
            ]);

            if($listpackage != 0){
                $packageDataProvider = new ArrayDataProvider([
                    'key'=>'package_id',
                    'allModels' => $listpackage,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
            } else {
                $packageDataProvider = new ArrayDataProvider([
                    'key'=>'package_id',
                    'allModels' => [],
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
            }

            if (Yii::$app->request->post()) {
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $connection->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

                $postData = Yii::$app->request->post();
                $analysisSave = 0;
                $saveSampleUpdate = 1;
                foreach ($postData['sample_ids'] as $sampleId) {

                    $sampleUpdate = Pstcsample::findOne($sampleId);
                    $tc = 0;
                    $st = 0;
                    if(empty($sampleUpdate->testcategory_id)) {
                        $sampleUpdate->testcategory_id = !empty($pstcData['testcategory_id']) ? (int) $pstcData['testcategory_id'] : NULL;
                        $tc = 1;
                    }

                    if(empty($sampleUpdate->sampletype_id)) {
                        $sampleUpdate->sampletype_id = !empty($pstcData['sampletype_id']) ? (int) $pstcData['sampletype_id'] : NULL;
                        $st = 1;
                    }

                    if($tc == 1 || $st == 1) {
                        if($sampleUpdate->save(false)) {
                            $saveSampleUpdate = 1;
                        } else {
                            $transaction->rollBack();
                            $saveSampleUpdate = 0;
                        }
                    }

                    $packageId = (int) $postData['package_id'];
                    $package_details = $this->get_package_detail_one($packageId);
                    $sample = Pstcsample::findOne($sampleId);

                    $package = new Pstcanalysis();
                    $package->rstl_id = (int) $rstlId;
                    $package->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                    $package->pstc_sample_id = (int) $sampleId;
                    $package->testname_id = 0;
                    $package->testname = '-';
                    $package->package_id = $packageId;
                    $package->package_name = $package_details['package']['name'];
                    $package->method_id = 0;
                    $package->method = '-';
                    $package->reference = '-';
                    $package->fee = $package_details['package']['rate'];
                    $package->testcategory_id = (int) $postData['Pstcanalysis']['testcategory_id'];
                    $package->sampletype_id = (int) $postData['Pstcanalysis']['sampletype_id'];
                    $package->quantity = 1; 
                    $package->is_package = 1;
                    //$package->type_fee_id = 2;
                    $package->is_package_name = 1;
                    $package->created_at = date('Y-m-d H:i:s');
                    $package->updated_at = date('Y-m-d H:i:s');

                    if($package->save(false)){
                        foreach ($package_details['testmethod_data'] as $test_method) {
                            $analysis = new Pstcanalysis();
                            $analysis->rstl_id = (int) $rstlId;
                            $analysis->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                            $analysis->pstc_sample_id = (int) $sampleId;
                            $analysis->testname_id = $test_method['testname_id'];
                            $analysis->testname = $test_method['testname'];
                            $analysis->method_id = (int) $test_method['method_id'];
                            $analysis->method = $test_method['method'];
                            $analysis->reference = $test_method['reference'];
                            $analysis->package_id = $packageId;
                            $analysis->fee = 0; //since package
                            $analysis->testcategory_id = (int) $postData['Pstcanalysis']['testcategory_id'];
                            $analysis->sampletype_id = (int) $postData['Pstcanalysis']['sampletype_id'];
                            $analysis->quantity = 1;
                            $analysis->is_package = 1;
                            //$analysis->type_fee_id = 2;
                            $analysis->created_at = date('Y-m-d H:i:s');
                            $analysis->updated_at = date('Y-m-d H:i:s');

                            if($analysis->save(false)){
                                $analysisSave = 1;
                            } else {
                                $analysisSave = 0;
                                goto packagefail;
                            }
                        }
                    } else {
                        $analysisSave = 0;
                        goto packagefail;
                    }
                }
                // $discount = $component->getDiscountOne($request->discount_id);
                // $rate = $discount->rate;
                // $fee = $connection->createCommand('SELECT SUM(fee) as subtotal FROM tbl_analysis WHERE request_id =:requestId')
                // ->bindValue(':requestId',$requestId)->queryOne();
                // $subtotal = $fee['subtotal'];
                // $total = $subtotal - ($subtotal * ($rate/100));
                // $request->total = $total;
                
                if($analysisSave == 1 && $saveSampleUpdate == 1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Package successfully saved.");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                } else {
                    goto packagefail;
                }
                packagefail: {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Package failed to save!");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                }
            }
        } else {
            return $this->redirect(['/site/login']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_formPackage', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampletype' => $this->listSampletype(),
                'sampleDataProvider' => $sampleDataProvider,
                'packageDataProvider' => $packageDataProvider,
            ]);
        } else {
            return $this->renderAjax('_formPackage', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampletype' => $this->listSampletype(),
                'sampleDataProvider' => $sampleDataProvider,
                'packageDataProvider' => $packageDataProvider,
            ]);
        }
    }

    public function actionUpdatepackage()
    {
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        if($rstlId > 0) {
            $requestId = (int) Yii::$app->request->get('request_id');
            //$testcategoryId = (int) Yii::$app->request->get('testcategory_id');
            //$sampletypeId = (int) Yii::$app->request->get('sampletype_id');
            $sampleId = (int) Yii::$app->request->get('sample_id');
            $analysisId = (int) Yii::$app->request->get('analysis_id');
            $packageId = (int) Yii::$app->request->get('package_id');

            $model = $this->findModel($analysisId);
            $sampleQuery = Pstcsample::find()->where('pstc_request_id = :requestId', [':requestId' => $requestId]);

            //if($testcategoryId > 0 && $sampletypeId > 0) {
            //    $listpackage = $this->listPackage($testcategoryId,$sampletypeId);
            //} else {
            $listpackage = $this->listPackage($model->testcategory_id,$model->sampletype_id);
            //}
    
            $sampleDataProvider = new ActiveDataProvider([
                'query' => $sampleQuery,
                'pagination' => false,
            ]);

            if($listpackage != 0){
                $packageDataProvider = new ArrayDataProvider([
                    'key'=>'package_id',
                    'allModels' => $listpackage,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
            } else {
                $packageDataProvider = new ArrayDataProvider([
                    'key'=>'package_id',
                    'allModels' => [],
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
            }

            if (Yii::$app->request->post()) {

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $connection->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

                $postData = Yii::$app->request->post();

                if($sampleId > 0 && $packageId > 0) {

                    $remove_package = Pstcanalysis::deleteAll('pstc_sample_id =:sampleId AND package_id =:packageId AND is_package =:isPackage', [':sampleId' => $sampleId,':packageId'=>$packageId,':isPackage'=>1]);

                    $analysisSave = 0;
                    $saveSampleUpdate = 1;
                    if($remove_package) {
                        $sampleId = (int) $postData['sample_id'];
                        $packageId = (int) $postData['package_id'];

                        $sampleUpdate = Pstcsample::findOne($sampleId);
                        $tc = 0;
                        $st = 0;
                        if(empty($sampleUpdate->testcategory_id)) {
                            $sampleUpdate->testcategory_id = !empty($pstcData['testcategory_id']) ? (int) $pstcData['testcategory_id'] : NULL;
                            $tc = 1;
                        }

                        if(empty($sampleUpdate->sampletype_id)) {
                            $sampleUpdate->sampletype_id = !empty($pstcData['sampletype_id']) ? (int) $pstcData['sampletype_id'] : NULL;
                            $st = 1;
                        }

                        if($tc == 1 || $st == 1) {
                            if($sampleUpdate->save(false)) {
                                $saveSampleUpdate = 1;
                            } else {
                                $transaction->rollBack();
                                $saveSampleUpdate = 0;
                            }
                        }
                        
                        $package_details = $this->get_package_detail_one($packageId);
                        $sample = Pstcsample::findOne($sampleId);

                        $package = new Pstcanalysis();
                        $package->rstl_id = (int) $rstlId;
                        $package->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                        $package->pstc_sample_id = (int) $sampleId;
                        $package->testname_id = 0;
                        $package->testname = '-';
                        $package->package_id = $packageId;
                        $package->package_name = $package_details['package']['name'];
                        $package->method_id = 0;
                        $package->method = '-';
                        $package->reference = '-';
                        $package->fee = $package_details['package']['rate'];
                        $package->testcategory_id = (int) $postData['Pstcanalysis']['testcategory_id'];
                        $package->sampletype_id = (int) $postData['Pstcanalysis']['sampletype_id'];
                        $package->quantity = 1; 
                        $package->is_package = 1;
                        //$package->type_fee_id = 2;
                        $package->is_package_name = 1;
                        $package->created_at = date('Y-m-d H:i:s');
                        $package->updated_at = date('Y-m-d H:i:s');

                        if($package->save(false)){
                            foreach ($package_details['testmethod_data'] as $test_method) {
                                $analysis = new Pstcanalysis();
                                $analysis->rstl_id = (int) $rstlId;
                                $analysis->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                                $analysis->pstc_sample_id = (int) $sampleId;
                                $analysis->testname_id = $test_method['testname_id'];
                                $analysis->testname = $test_method['testname'];
                                $analysis->method_id = (int) $test_method['method_id'];
                                $analysis->method = $test_method['method'];
                                $analysis->reference = $test_method['reference'];
                                $analysis->package_id = $packageId;
                                $analysis->fee = 0; //since package
                                $analysis->testcategory_id = (int) $postData['Pstcanalysis']['testcategory_id'];
                                $analysis->sampletype_id = (int) $postData['Pstcanalysis']['sampletype_id'];
                                $analysis->quantity = 1;
                                $analysis->is_package = 1;
                                //$analysis->type_fee_id = 2;
                                $analysis->created_at = date('Y-m-d H:i:s');
                                $analysis->updated_at = date('Y-m-d H:i:s');

                                if($analysis->save(false)){
                                    $analysisSave = 1;
                                } else {
                                    $analysisSave = 0;
                                    goto packagefail;
                                }
                            }
                        } else {
                            $analysisSave = 0;
                            goto packagefail;
                        }
                        
                        if($analysisSave == 1 && $saveSampleUpdate == 1) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', "Package successfully updated.");
                            return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                        } else {
                            goto packagefail;
                        }
                    } else {
                        goto packagefail;
                    }
                } else {
                    goto packagefail;
                }
                packagefail: {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Failed to update package!");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                }
            }
        } else {
            return $this->redirect(['/site/login']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_formPackage', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampletype' => $this->listSampletype(),
                'sampleDataProvider' => $sampleDataProvider,
                'packageDataProvider' => $packageDataProvider,
            ]);
        } else {
            return $this->renderAjax('_formPackage', [
                'model' => $model,
                'testcategory' => $this->listTestcategory(),
                'sampletype' => $this->listSampletype(),
                'sampleDataProvider' => $sampleDataProvider,
                'packageDataProvider' => $packageDataProvider,
            ]);
        }
    }

    public function actionDeletepackage($sample_id,$package_id,$request_id)
    {
        if(Yii::$app->request->post()) {
            $sampleId = (int) $sample_id;
            $packageId = (int) $package_id;

            if($sampleId > 0 && $packageId > 0){
                $delete = Pstcanalysis::deleteAll('pstc_sample_id =:sampleId AND package_id =:packageId AND is_package =:isPackage', [':sampleId' => $sampleId,':packageId'=>$packageId,':isPackage'=>1]);
                if($delete){
                    Yii::$app->session->setFlash('success', "Package successfully removed.");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $request_id]);
                } else {
                    Yii::$app->session->setFlash('error', "Failed to remove package!");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $request_id]);
                }
            } else {
                Yii::$app->session->setFlash('error', "Invalid action!");
                return $this->redirect(['/pstc/pstcrequest/view', 'id' => $request_id]);
            }
        } else {
            Yii::$app->session->setFlash('error', "Invalid action!");
            return $this->redirect(['/pstc/pstcrequest/view', 'id' => $request_id]);
        }
    }

    /******* for analysis not offered *******/
    public function actionAdd_not_offer()
    {
        $model = new Pstcanalysis;

        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        if($rstlId > 0) {
            $requestId = (int) Yii::$app->request->get('request_id');
            $testnameId = (int) Yii::$app->request->get('testname_id');
            $sampleQuery = Pstcsample::find()->where('pstc_request_id = :requestId', [':requestId' => $requestId]);
    
            $sampleDataProvider = new ActiveDataProvider([
                'query' => $sampleQuery,
                'pagination' => false,
            ]);

            $methods = !empty($this->listReferralMethodreference($testnameId)) ? $this->listReferralMethodreference($testnameId) : [];

            $methodrefDataProvider = new ArrayDataProvider([
                //'key'=>'testname_method_id',
                'allModels' => $methods,
                'pagination' => [
                    'pageSize' => 10,
                ],
                //'pagination'=>false,
            ]);

            if(Yii::$app->request->post()) {

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $postData = Yii::$app->request->post();
                $pstcData = Yii::$app->request->post('Pstcanalysis');

                $analysisSave = 0;
                $saveSampleUpdate = 1;
                foreach ($postData['sample_ids'] as $sample) {

                    $sampleUpdate = Pstcsample::findOne($sample);
                    if(empty($sampleUpdate->sampletype_id)) {
                        $sampleUpdate->sampletype_id = !empty($pstcData['sampletype_id']) ? (int) $pstcData['sampletype_id'] : NULL;
                        if($sampleUpdate->save(false)) {
                            $saveSampleUpdate = 1;
                        } else {
                            $transaction->rollBack();
                            $saveSampleUpdate = 0;
                        }
                    }

                    $testnameId = (int) $pstcData['testname_id'];
                    $methodrefId = (int) $postData['method_id'];
                    $test = $this->getReferralTestnameOne($testnameId);
                    $method = $this->getReferralMethodrefOne($methodrefId);

                    $analysis = new Pstcanalysis();
                    $analysis->pstc_sample_id = (int) $sample;
                    $analysis->rstl_id = (int) $rstlId;
                    $analysis->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                    $analysis->testname_id = (int) $testnameId;
                    $analysis->testname = $test->test_name;
                    $analysis->method_id = (int) $methodrefId;
                    $analysis->method = $method->method;
                    $analysis->reference = $method->reference;
                    $analysis->fee = $method->fee;
                    $analysis->quantity = 1;
                    $analysis->testcategory_id = 0;
                    $analysis->sampletype_id = (int) $pstcData['sampletype_id'];
                    $analysis->created_at = date('Y-m-d H:i:s');
                    $analysis->updated_at = date('Y-m-d H:i:s');

                    if(!$analysis->save(false)){
                        goto analysisfail;
                    } else {
                        $analysisSave = 1;
                    }
                }

                if($analysisSave == 1  && $saveSampleUpdate == 1) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Analysis successfully saved.");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                } else {
                    goto analysisfail;
                }
                analysisfail: {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Analysis failed to save!");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                }
            }
        } else {
            return $this->redirect(['/site/login']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_formNotoffered', [
                'model' => $model,
                'sampletype' => $this->listReferralSampletype(),
                'testname' => $this->listReferralTestname(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]);
        } else {
            return $this->renderAjax('_formNotoffered', [
                'model' => $model,
                'sampletype' => $this->listReferralSampletype(),
                'testname' => $this->listReferralTestname(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]);
        }
    }

    public function actionUpdate_not_offer($id)
    {
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        if($rstlId > 0) {
            $requestId = (int) Yii::$app->request->get('request_id');
            $testnameId = (int) Yii::$app->request->get('testname_id');
            $sampleQuery = Pstcsample::find()->where('pstc_request_id = :requestId', [':requestId' => $requestId]);

            $model = $this->findModel($id);
    
            $sampleDataProvider = new ActiveDataProvider([
                'query' => $sampleQuery,
                'pagination' => false,
            ]);

            $methods = !empty($this->listReferralMethodreference($model->testname_id)) ? $this->listReferralMethodreference($model->testname_id) : [];

            $methodrefDataProvider = new ArrayDataProvider([
                //'key'=>'testname_method_id',
                'allModels' => $methods,
                'pagination' => [
                    'pageSize' => 10,
                ],
                //'pagination'=>false,
            ]);

            if(Yii::$app->request->post()) {

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $postData = Yii::$app->request->post();
                $pstcData = Yii::$app->request->post('Pstcanalysis');
                $sampleId = (int) $postData['sample_id'];

                $saveSampleUpdate = 1;
                $sampleUpdate = Pstcsample::findOne($sampleId);
                if(empty($sampleUpdate->sampletype_id)) {
                    $sampleUpdate->sampletype_id = !empty($pstcData['sampletype_id']) ? (int) $pstcData['sampletype_id'] : NULL;
                    if($sampleUpdate->save(false)) {
                        $saveSampleUpdate = 1;
                    } else {
                        $transaction->rollBack();
                        $saveSampleUpdate = 0;
                    }
                }

                $analysisSave = 0;
                $testnameId = (int) $pstcData['testname_id'];
                $methodrefId = (int) $postData['method_id'];
                $test = $this->getReferralTestnameOne($testnameId);
                $method = $this->getReferralMethodrefOne($methodrefId);

                $model->pstc_sample_id = $sampleId;
                $model->rstl_id = (int) $rstlId;
                $model->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
                $model->testname_id = (int) $testnameId;
                $model->testname = $test->test_name;
                $model->method_id = (int) $methodrefId;
                $model->method = $method->method;
                $model->reference = $method->reference;
                $model->fee =$method->fee;
                $model->quantity = 1;
                //$analysis->testcategory_id = 0;
                $model->sampletype_id = (int) $pstcData['sampletype_id'];
                $model->updated_at = date('Y-m-d H:i:s');

                if(!$model->save(false)){
                    $analysisSave = 0;
                    goto analysisfail;
                } else {
                    $analysisSave = 1;
                }

                if($analysisSave == 1 && $saveSampleUpdate == 1){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Analysis successfully updated.");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                } else {
                    goto analysisfail;
                }

                analysisfail: {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Analysis failed to update!");
                    return $this->redirect(['/pstc/pstcrequest/view', 'id' => $requestId]);
                }
            }
        } else {
            return $this->redirect(['/site/login']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_formNotoffered', [
                'model' => $model,
                'sampletype' => $this->listReferralSampletype(),
                'testname' => $this->listReferralTestname(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]);
        } else {
            return $this->renderAjax('_formNotoffered', [
                'model' => $model,
                'sampletype' => $this->listSampletype(),
                'testname' => $this->listReferralTestname(),
                'sampleDataProvider' => $sampleDataProvider,
                'methodrefDataProvider' => $methodrefDataProvider,
            ]);
        }
    }

    //list test category, data taken from eulims_lab table test category
    protected function listTestcategory()
    {
        $testcategory = ArrayHelper::map(Testcategory::find()->all(), 'testcategory_id', 
            function($testcategory, $defaultValue) {
                return $testcategory->category;
        });

        return $testcategory;
    }

    //list sample type, data taken from eulims_lab table sample type
    protected function listSampletype()
    {
        $query = Sampletype::find()
            ->joinWith(['labSampletypes'],false)
            //->where('tbl_lab_sampletype.testcategory_id = :testcategoryId', [':testcategoryId' => $testcategoryId])
            ->groupBy('tbl_sampletype.sampletype_id')
            ->all();

        $sampletype = ArrayHelper::map($query, 'sampletype_id', 
            function($sampletype, $defaultValue) {
                return $sampletype->type;
        });

        return $sampletype;
    }

    //list referral sample type, data taken from eulims_referral_lab table sample type
    protected function listReferralSampletype()
    {
        $query = \common\models\referral\Sampletype::find()
            ->joinWith(['labsampletypes'],false)
            //->where('tbl_lab_sampletype.testcategory_id = :testcategoryId', [':testcategoryId' => $testcategoryId])
            ->groupBy('tbl_sampletype.sampletype_id')
            ->all();

        $sampletype = ArrayHelper::map($query, 'sampletype_id', 
            function($sampletype, $defaultValue) {
                return $sampletype->type;
        });

        return $sampletype;
    }

    //list test name, data taken from eulims_lab table test name
    protected function listTestname()
    {
        $query = Testname::find()
            ->joinWith(['sampletypes'],false)
            //->where('tbl_sampletype_testname.sampletype_id = :sampletypeId', [':sampletypeId' => $sampletypeId])
            ->groupBy('tbl_testname.testname_id')
            ->all();

        $testname = ArrayHelper::map($query, 'testname_id', 
            function($testname, $defaultValue) {
                return $testname->testName;
        });

        return $testname;
    }

    //list referral test name, data taken from eulims_referral_lab table test name
    protected function listReferralTestname()
    {
        $query = \common\models\referral\Testname::find()
            ->joinWith(['sampletypetestnames'],false)
            //->where('tbl_sampletype_testname.sampletype_id = :sampletypeId', [':sampletypeId' => $sampletypeId])
            ->groupBy('tbl_testname.testname_id')
            ->all();

        $testname = ArrayHelper::map($query, 'testname_id', 
            function($testname, $defaultValue) {
                return $testname->test_name;
        });

        return $testname;
    }

    protected function getTestnameOne($testnameId)
    {
        if(($model = Testname::findOne($testnameId)) !== null) {
            return $model;
        }
    }

    protected function getMethodrefOne($methodrefId)
    {
        if(($model = Methodreference::findOne($methodrefId)) !== null) {
            return $model;
        }
    }

    protected function getReferralTestnameOne($testnameId)
    {
        if(($model = \common\models\referral\Testname::findOne($testnameId)) !== null) {
            return $model;
        }
    }

    protected function getReferralMethodrefOne($methodrefId)
    {
        if(($model = \common\models\referral\Methodreference::findOne($methodrefId)) !== null) {
            return $model;
        }
    }

    //get lists of sampletype by test category id
    public function actionGet_sampletype()
    {
        $testcategoryId = (int) Yii::$app->request->get('testcategory_id');

        if($testcategoryId > 0)
        {
            $sampletype = Sampletype::find()
                //->select('tbl_sampletype.*')
                ->joinWith(['labSampletypes'],false)
                ->where('tbl_lab_sampletype.testcategory_id = :testcategoryId', [':testcategoryId' => $testcategoryId])
                //->asArray()
                ->groupBy('tbl_sampletype.sampletype_id')
                ->all();

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(count($sampletype) > 0)
            {
                foreach($sampletype as $list) {
                    $data[] = ['id' => $list->sampletype_id, 'text' => $list->type];
                }
                //$data = ['id' => '', 'text' => 'No results found'];
            } else {
                $data = ['id' => '', 'text' => 'No results found'];
            }
        } else {
            $data = ['id' => '', 'text' => 'No results found'];
        }
        return ['data' => $data];
    }

    //get lists of testname by sample type id
    public function actionGet_testname()
    {
        //$sampletypeId = (int) Yii::$app->request->get('sampletype_id');
        $sampletypeId = Yii::$app->request->get('sampletype_id');
        //$sampletypeId = "9,10";
        //print_r(explode(',', $sampletypeId));
        //exit;
        //$sampletypeIds = implode(',', $sampletypeId);
        if(count($sampletypeId) > 0)
        {
            $testname = Testname::find()
                //->select('tbl_sampletype.*')
                ->joinWith(['sampletypes'],false)
                //->where('tbl_sampletype_testname.sampletype_id = :sampletypeId', [':sampletypeId' => $sampletypeId])
                //->where('tbl_sampletype_testname.sampletype_id IN (:sampletypeId)', [':sampletypeId' => explode(",", $sampletypeIds)])
                //->asArray()
                ->where([
                    'tbl_sampletype_testname.sampletype_id' => $sampletypeId,
                ])
                ->groupBy('tbl_testname.testname_id')
                ->all();

              /*  $data = (new \yii\db\Query())
                    ->from('eulims_referral_lab.tbl_sampletypetestname')
                    ->join('INNER JOIN', 'eulims_referral_lab.tbl_testname', 'tbl_sampletypetestname.testname_id = tbl_testname.testname_id')
                    ->where([
                        //'sampletype_id' => [1,2],
                        'sampletype_id' => explode(',', $sampletypeId),
                    ])
                    //->where('sampletype_id=:sampletypeId', [':sampletype_id' => [1,2]])
                    ->groupBy('tbl_testname.testname_id')
                    //->orderBy('sampletype_id,tbl_sampletypetestname.testname_id')
                    ->orderBy('tbl_sampletypetestname.testname_id')
                    //->asArray()
                    ->all(); */

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(count($testname)>0)
            {
                foreach($testname as $list) {
                    $data[] = ['id' => $list->testname_id, 'text' => $list->testName];
                }
                //$data = ['id' => '', 'text' => 'No results found'];
            } else {
                $data = ['id' => '', 'text' => 'No results found'];
            }
        } else {
            $data = ['id' => '', 'text' => 'No results found'];
        }
        return ['data' => $data];
    }

    //get referral lists of testname by sample type id for adding analysis not offered
    public function actionGet_referral_testname()
    {
        $sampletypeId = (int) Yii::$app->request->get('sampletype_id');

        if($sampletypeId > 0)
        {
            $testname = \common\models\referral\Testname::find()
                //->select('tbl_sampletype.*')
                ->joinWith(['sampletypetestnames'],false)
                ->where('tbl_sampletypetestname.sampletype_id = :sampletypeId', [':sampletypeId' => $sampletypeId])
                //->asArray()
                ->groupBy('tbl_testname.testname_id')
                ->all();

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(count($testname)>0)
            {
                foreach($testname as $list) {
                    $data[] = ['id' => $list->testname_id, 'text' => $list->test_name];
                }
                //$data = ['id' => '', 'text' => 'No results found'];
            } else {
                $data = ['id' => '', 'text' => 'No results found'];
            }
        } else {
            $data = ['id' => '', 'text' => 'No results found'];
        }
        return ['data' => $data];
    }


    //get method reference by test name id
    protected function listMethodreference($testnameId)
    {
        if($testnameId > 0) {
            $data = Testnamemethod::find()
                ->joinWith(['testname','method'])
                //->where(['tbl_labsampletype.lab_id' => $labId])
                ->where('tbl_testname_method.testname_id = :testnameId', [':testnameId' => (int) $testnameId])
                ->asArray()
                ->all();
        } else {
            $data = [];
        }
        
        return $data;
    }

    //get referral method reference by test name id
    protected function listReferralMethodreference($testnameId)
    {
        if($testnameId > 0) {
            $data = \common\models\referral\Testnamemethod::find()
                ->joinWith(['testname','methodreference'])
                //->where(['tbl_labsampletype.lab_id' => $labId])
                ->where('tbl_testname_method.testname_id = :testnameId', [':testnameId' => (int) $testnameId])
                ->asArray()
                ->all();
        } else {
            $data = [];
        }
        
        return $data;
    }

    //get package list
    protected function listPackage($testcategoryId,$sampletypeId)
    {
        $query = Packagelist::find()
            ->where(['testcategory_id'=>$testcategoryId])
            ->andWhere(['sample_type_id'=>$sampletypeId]);
        
        $package = $query->all();
        $count = $query->count();
        if($count > 0){
            return $package;                    
        } else {
            return 0;
        }
    }

    //load method reference
    public function actionGet_testnamemethod()
    {
        $analysisId = (int) Yii::$app->request->get('analysis_id');
        $testnameId = (int) Yii::$app->request->get('testname_id');
        if ($analysisId > 0) {
            $model = $this->findModel($analysisId);
        } else {
            $model = new Pstcanalysis();
        }

        if ($testnameId > 0) {
            $methods = $this->listMethodreference($testnameId);
        } else {
            $methods = [];
        }

        if (Yii::$app->request->isAjax) {
            $methodrefDataProvider = new ArrayDataProvider([
                //'key'=>'sample_id',
                'allModels' => $methods,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            
            return $this->renderAjax('_methodreference', [
                'methodProvider' => $methodrefDataProvider,
                'model' => $model,
            ]);
        }
    }

    //load referral method reference
    public function actionGet_referral_testnamemethod()
    {
        $analysisId = (int) Yii::$app->request->get('analysis_id');
        $testnameId = (int) Yii::$app->request->get('testname_id');

        if ($analysisId > 0) {
            $model = $this->findModel($analysisId);
        } else {
            $model = new Pstcanalysis();
        }

        if ($testnameId > 0) {
            $methods = $this->listReferralMethodreference($testnameId);
        } else {
            $methods = [];
        }

        if (Yii::$app->request->isAjax) {
            $methodrefDataProvider = new ArrayDataProvider([
                //'key'=>'sample_id',
                'allModels' => $methods,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            
            return $this->renderAjax('_referralmethodreference', [
                'methodProvider' => $methodrefDataProvider,
                'model' => $model,
            ]);
        }
    }

    //get default pagination page on load for the checked method reference
    public function actionGetdefaultpage()
    {
        $analysisId = (int) Yii::$app->request->get('analysis_id');

        //per page pagination should be the same
        //default page size of the method reference dataprovider
        $perpage = 10;
        if($analysisId > 0) {
            $model = $this->findModel($analysisId);

            $cpage = (new \yii\db\Query())
                ->select('CEIL(COUNT(*)/'.$perpage.') AS count_page')
                ->from('eulims_lab.tbl_methodreference')
                ->join('INNER JOIN', 'eulims_lab.tbl_testname_method', 'tbl_methodreference.method_reference_id = tbl_testname_method.method_id')
                ->where(['tbl_testname_method.testname_id'=>$model->testname_id])
                ->andWhere(['<=','tbl_methodreference.method_reference_id',$model->method_id])
                //->groupBy('tbl_testname.testname_id')
                ->orderBy('tbl_methodreference.method_reference_id')
                ->one();
            $data = $cpage['count_page'];
        } else {
            $data = [];
        }
        
        return $data;
    }

    //get default pagination page on load for the checked method reference for referral
    public function actionGetreferraldefaultpage()
    {
        $analysisId = (int) Yii::$app->request->get('analysis_id');

        //per page pagination should be the same
        //default page size of the method reference dataprovider
        $perpage = 10;
        if($analysisId > 0) {
            $model = $this->findModel($analysisId);

            $cpage = (new \yii\db\Query())
                ->select('CEIL(COUNT(*)/'.$perpage.') AS count_page')
                ->from('eulims_referral_lab.tbl_methodreference')
                ->join('INNER JOIN', 'eulims_referral_lab.tbl_testname_method', 'tbl_methodreference.methodreference_id = tbl_testname_method.methodreference_id')
                ->where(['tbl_testname_method.testname_id'=>$model->testname_id])
                ->andWhere(['<=','tbl_methodreference.methodreference_id',$model->method_id])
                //->groupBy('tbl_testname.testname_id')
                ->orderBy('tbl_methodreference.methodreference_id')
                ->one();
            $data = $cpage['count_page'];
        } else {
            $data = [];
        }
        
        return $data;
    }

    public function actionGet_package()
    {
        $testcategoryId = (int) Yii::$app->request->get('testcategory_id');
        $sampletypeId = (int) Yii::$app->request->get('sampletype_id');
        $analysisId = (int) Yii::$app->request->get('analysis_id');

        if ($analysisId > 0) {
            $model = $this->findModel($analysisId);
        } else {
            $model = new Pstcanalysis();
        }

        if($testcategoryId > 0 && $sampletypeId > 0)
        {
            $listpackage = $this->listPackage($testcategoryId,$sampletypeId);
        } else {
            $listpackage = 0;
        }

        if($listpackage != 0) {
            $packageDataProvider = new ArrayDataProvider([
                'key'=>'package_id',
                'allModels' => $listpackage,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } else {
            $packageDataProvider = new ArrayDataProvider([
                'key'=>'package_id',
                'allModels' => [],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_packages', [
                'packageDataProvider' => $packageDataProvider,
                'model' => $model,
            ]);
        }
    }

    public function actionPackage_detail() {
        if (Yii::$app->request->post('expandRowKey')) {
            $packageId = (int) Yii::$app->request->post('expandRowKey');
            $testname_method = $this->get_package_detail_one($packageId);
            if(!empty($testname_method['testmethod_data']) && $testname_method != 0){
                $testname_methodDataProvider = new ArrayDataProvider([
                    'key'=>'testname_method_id',
                    'allModels' => $testname_method['testmethod_data'],
                    'pagination' => false,
                ]);
                return $this->renderPartial('_package-details', ['testname_methodDataProvider'=>$testname_methodDataProvider]);
            } else {
                return '<div class="alert alert-danger">No data found</div>';
            }
        } else {
            return '<div class="alert alert-danger">No data found</div>';
        }
    }

    protected function get_package_detail_one($packageId)
    {
        if($packageId > 0) {
            $package = Packagelist::find()->where(['package_id'=>$packageId])->one();
            
            if(!empty($package->tests)){
                $testmethods = explode(",",$package->tests);
                $testmethod_data = [];
                foreach ($testmethods as $testmethodId){
                    $testmethodrefs = Testnamemethod::findOne($testmethodId);
                    $listTestmethods = [
                        'testname_method_id'=>$testmethodrefs->testname_method_id,
                        'testname_id'=>$testmethodrefs->testname_id,
                        'testname'=>$testmethodrefs->testname->testName,
                        'method_id'=>$testmethodrefs->method_id,
                        'method'=>$testmethodrefs->method->method,
                        'reference'=>$testmethodrefs->method->reference,
                    ];
                    array_push($testmethod_data, $listTestmethods);
                }
                return ['testmethod_data'=>$testmethod_data,'package'=>$package];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
