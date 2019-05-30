<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Service;
use common\models\referral\ServiceSearch;
use common\models\referral\Lab;
use common\models\referral\Sampletype;
use common\models\referral\Testname;
use common\models\referral\Methodreference;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\lab\Request;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use linslin\yii2\curl;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class ServiceController extends Controller
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
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        set_time_limit(120);
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

        $labreferral = ArrayHelper::map(Lab::find()->asArray()->all(), 'lab_id', 'labname');

        if(Yii::$app->request->get('testname_id')>0 && Yii::$app->request->get('sampletype_id')>0 && Yii::$app->request->get('lab_id')>0){
            $testnameId = (int) Yii::$app->request->get('testname_id');
            $sampletypeId = (int) Yii::$app->request->get('sampletype_id');
            $labId = (int) Yii::$app->request->get('lab_id');
            $methods = $this->listReferralmethodref($labId,$sampletypeId,$testnameId);
        } else {
            $methods = [];
        }

        $methodrefDataProvider = new ArrayDataProvider([
            //'key'=>'sample_id',
            'allModels' => $methods,
            'pagination' => [
                'pageSize' => 10,
            ],
            //'pagination'=>false,
        ]);

        return $this->render('index',[
            'laboratory' => $labreferral,
            'sampletype' => [],
            'testname' => [],
            'methodrefDataProvider' => $methodrefDataProvider,
            'count_methods' => count($methods),
        ]);
    }

    //agency offer service
    public function actionOffer()
    {
        set_time_limit(120);
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        if(count(Yii::$app->request->post('methodref_ids')) > 0 && $rstlId > 0){
            $connection= Yii::$app->referraldb;
            $connection->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
            $transaction = $connection->beginTransaction();
            
            $methodrefIds = json_decode(Yii::$app->request->post('methodref_ids'),true);
            $agencyId = (int) Yii::$app->user->identity->profile->rstl_id;
            
            $listMethodrefIds = rtrim(implode(",",$methodrefIds));
            $service = $connection->createCommand("SELECT count(*) FROM tbl_service WHERE agency_id=:agencyId AND method_ref_id IN (".$listMethodrefIds.")")
              ->bindValue(':agencyId', $agencyId);

            if($service->queryScalar() > 0){
                $transaction->rollBack();
                $return = 2; //has duplicate
            } else {
                foreach($methodrefIds as $methodref_id){
                    $model = new Service();
                    $model->agency_id = (int) $agencyId;
                    $model->method_ref_id = (int) $methodref_id;
                    $model->offered_date = date('Y-m-d H:i:s');
                    if($model->save()){
                        $saved = 1; //success
                    } else {
                        $transaction->rollBack();
                        $saved = 0; //fail
                    }
                }
                if($saved == 1){
                    $transaction->commit();
                    $return = 1; //success
                } else {
                    $transaction->rollBack();
                    $return = 0; //fail
                }
            }
        } else {
            $return = 0;
        }
        //return 2 is duplicate
        //return 1 is success
        //return 0 is fail save
        return $return;
    }
    //agency offer service
    public function actionRemove()
    {
        set_time_limit(120);
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        if(count(Yii::$app->request->post('methodref_ids')) > 0 && $rstlId > 0){
            $connection= Yii::$app->referraldb;
            $connection->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
            $transaction = $connection->beginTransaction();
            
            $methodrefIds = json_decode(Yii::$app->request->post('methodref_ids'),true);
            $agencyId = (int) Yii::$app->user->identity->profile->rstl_id;
            
            $listMethodrefIds = rtrim(implode(",",$methodrefIds));
            $service = $connection->createCommand("DELETE FROM tbl_service WHERE agency_id=:agencyId AND method_ref_id IN (".$listMethodrefIds.")")
               ->bindValue(':agencyId', $agencyId);
               
            if($service->execute()){
                $transaction->commit();
                $return = 1;
            } else {
                $transaction->rollBack();
                $return = 0;
            }
        } else {
            $return = 0;
        }
        return $return;
    }

    /**
     * Displays a single Service model.
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
     * Creates a new Service model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Service();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->service_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->service_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Service model.
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
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList_sampletype() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = (int) end($_POST['depdrop_parents']);
            if ($id != null) {
                $list = Sampletype::find()
                    ->joinWith('labsampletypes')
                    ->where('tbl_labsampletype.lab_id = :labId', [':labId' => $id])
                    ->asArray()->all();

                if(count($list) > 0){
                    //$selected = '';
                    foreach ($list as $i => $sampletype) {
                        $out[] = ['id' => $sampletype['sampletype_id'], 'name' => $sampletype['type']];
                        //if ($i == 0) {
                            //$selected = $sampletype['sampletype_id'];
                        //}
                    }
                    // Shows how you can preselect a value
                    //echo Json::encode(['output' => $out, 'selected'=>$selected]);
                    \Yii::$app->response->data = Json::encode(['output'=>$out]);
                    return;
                }
            }
        }
        //echo Json::encode(['output' => '', 'selected'=>'']);
        echo Json::encode(['output'=>'']);
    }

    public function actionList_testname() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $labId = (int) ($_POST['depdrop_parents'][0]);
            $sampletypeId = (int) ($_POST['depdrop_parents'][1]);
            if ($labId > 0 && $sampletypeId > 0) {
                $list = (new \yii\db\Query())
                        ->select('tbl_testname.*')
                        ->from('eulims_referral_lab.tbl_labsampletype')
                        ->join('INNER JOIN', 'eulims_referral_lab.tbl_sampletypetestname', 'tbl_labsampletype.sampletype_id = tbl_sampletypetestname.sampletype_id')
                        ->join('INNER JOIN', 'eulims_referral_lab.tbl_testname', 'tbl_sampletypetestname.testname_id = tbl_testname.testname_id')
                        ->where('tbl_sampletypetestname.sampletype_id =:sampletypeId AND lab_id =:labId',[':sampletypeId'=>$sampletypeId,':labId'=>$labId])
                        ->groupBy('tbl_testname.testname_id')
                        ->orderBy('tbl_sampletypetestname.testname_id')
                        //->asArray()
                        ->all();

                if(count($list) > 0){
                    //$selected = '';
                    foreach ($list as $i => $testname) {
                        $out[] = ['id' => $testname['testname_id'], 'name' => $testname['test_name']];
                        //if ($i == 0) {
                        //    $selected = $testname['testname_id'];
                        //}
                    }
                    // Shows how you can preselect a value
                    //echo Json::encode(['output' => $out, 'selected'=>$selected]);
                    \Yii::$app->response->data = Json::encode(['output'=>$out]);
                    return;
                }
            }
        }
        //echo Json::encode(['output' => '', 'selected'=>'']);
        echo Json::encode(['output'=>'']);
    }

    //get referral method reference
    protected function listReferralmethodref($labId,$sampletypeId,$testnameId)
    {
        if(isset($testnameId) && isset($sampletypeId) && isset($labId))
        {
            if($testnameId > 0 && $sampletypeId > 0 && $labId > 0){               
                $params = [
                    ':labId'=>$labId,
                    ':sampletypeId'=>$sampletypeId,
                    ':testnameId'=>$testnameId
                ];
                $query = Yii::$app->referraldb->createCommand("
                    CALL spGetMethodReference(:labId,:sampletypeId,:testnameId)");
                $query->bindValues($params);
                if($query->queryScalar() === false){
                    $data = [];
                } else {
                    $data = $query->queryAll();
                }
            } else {
                $data = [];
            }

        } else {
            $data =[];
        }
        return $data;
    }

    public function actionGettestnamemethod()
    {
        set_time_limit(120);
        $testnameId = (int) Yii::$app->request->get('testname_id');
        $sampletypeId = (int) Yii::$app->request->get('sampletype_id');
        $labId = (int) Yii::$app->request->get('lab_id');
        if ($testnameId > 0 && $sampletypeId > 0 && $labId > 0){
            $methods = $this->listReferralmethodref($labId,$sampletypeId,$testnameId);
        }
        else {
            $methods = [];
        }

        if (Yii::$app->request->isAjax) {
            $methodrefDataProvider = new ArrayDataProvider([
                //'key'=>'sample_id',
                'allModels' => $methods,
                'pagination' => [
                    'pageSize' => 10,
                ],
                //'pagination'=>false,
            ]);
            return $this->renderAjax('_methodreference', [
                'methodrefDataProvider' => $methodrefDataProvider,
                //'model' => $model,
                'count_methods' => count($methods),
            ]);
        }
    }
}
