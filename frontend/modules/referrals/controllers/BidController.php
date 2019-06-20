<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Bid;
use common\models\referral\BidSearch;
use common\models\referral\Referral;
use common\models\referral\Analysis;
use common\models\referral\Testbid;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * BidController implements the CRUD actions for Bid model.
 */
class BidController extends Controller
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
     * Lists all Bid models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BidSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bid model.
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
     * Creates a new Bid model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->request->get('referral_id')){
            $referralId = (int) Yii::$app->request->get('referral_id');
            $referral = $this->findReferral($referralId);
        } else {
            Yii::$app->session->setFlash('error', "Referral ID not valid!");
            return $this->redirect(['/referrals/notification']);
        }

        $model = new Bid();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->bid_id]);
            return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
        }

        /*return $this->render('create', [
            'model' => $model,
        ]);*/

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bid model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bid_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Bid model.
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
     * Finds the Bid model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bid the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bid::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

    //place bid
    public function actionPlacebid()
    {
        if(Yii::$app->request->get('referral_id')){
            $referralId = (int) Yii::$app->request->get('referral_id');
            $referral = $this->findReferral($referralId);
        } else {
            Yii::$app->session->setFlash('error', "Referral ID not valid!");
            return $this->redirect(['/referrals/notification']);
        }

        $model = new Bid();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->bid_id]);
            return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
        }

        /*return $this->render('create', [
            'model' => $model,
        ]);*/

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionTemporary()
    {
        $connection= Yii::$app->db;
        $connection->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
        $transaction = $connection->beginTransaction();

        $agencyId = (int) Yii::$app->user->identity->profile->rstl_id;

        if(Yii::$app->request->get('referral_id')){
            $referralId = (int) Yii::$app->request->get('referral_id');
            $referral = $this->findReferral($referralId);
            $bid = Bid::find()->where('referral_id =:referralId AND bidder_agency_id =:agencyId',[':referralId'=>$referralId,':agencyId'=>$agencyId])->count();
        } else {
            Yii::$app->session->setFlash('error', "Referral ID not valid!");
            return $this->redirect(['/referrals/notification']);
        }

        $temp_table = $connection->createCommand("
            -- CREATE TEMPORARY TABLE test_to_bid
            SELECT s.sample_name, s.sample_code, a.*
            FROM tbl_analysis a
            INNER JOIN tbl_sample s ON a.sample_id = s.sample_id
            INNER JOIN tbl_referral r ON s.referral_id = r.referral_id
            WHERE r.referral_id = 1
            ")->execute();
        /*$insert_temp_table = $connection
            ->createCommand(
                "SELECT a.*
                FROM tbl_analysis a
                INNER JOIN tbl_sample s ON a.sample_id = s.sample_id
                INNER JOIN tbl_referral r ON s.referral_id = r.referral_id
                WHERE r.referral_id = 1"
                )->queryColumn();*/

        //$analysis = $connection->createCommand("SELECT * FROM test_to_bid")->queryAll();

        //print_r($analysis);
        //print_r($temp_table);
        //if (Yii::$app->request->post()) {
            //return $this->redirect(['view', 'id' => $model->bid_id]);
            //return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
            //print_r(Yii::$app->request->post());
            //exit;
            //$fee = '000'
            //$are = Yii::$app->db->createCommand()->update('test_to_bid', ['analysis_fee' => 101], 'analysis_id = 1')->execute();
            //$are = Yii::$app->db->createCommand()->update('test_to_bid', ['analysis_fee' => 102], 'analysis_id = 2')->execute();
            //$are = Yii::$app->db->createCommand()->update('test_to_bid', ['analysis_fee' => 103], 'analysis_id = 3')->execute();
            //$a = $connection->createCommand("SELECT * FROM test_to_bid")->queryAll();
            //echo "<pre>";
            //print_r($a);
            //echo "</pre>";
        //}

        // validate if there is a editable input saved via AJAX
        /*if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $bookId = Yii::$app->request->post('editableKey');
            $model = Analysis::findOne($bookId);

            // store a default json response as desired by editable
            $out = Json::encode(['output'=>'', 'message'=>'']);

            // fetch the first entry in posted data (there should only be one entry 
            // anyway in this array for an editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $posted = current($_POST['Analysis']);
            $post = ['Analysis' => $posted];

            // load model like any single model validation
            if ($model->load($post)) {
            // can save model or do something before saving model
            $model->save();

            // custom output to return to be displayed as the editable grid cell
            // data. Normally this is empty - whereby whatever value is edited by
            // in the input by user is updated automatically.
            $output = '';

            // specific use case where you need to validate a specific
            // editable column posted when you have more than one
            // EditableColumn in the grid view. We evaluate here a
            // check to see if buy_amount was posted for the Book model
            if (isset($posted['fee'])) {
            $output = Yii::$app->formatter->asDecimal($model->fee, 2);
            }

            // similarly you can check if the name attribute was posted as well
            // if (isset($posted['name'])) {
            // $output = ''; // process as you need
            // }
            $out = Json::encode(['output'=>$output, 'message'=>'']);
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
        }*/


        /*if($temp_table > 0){
            //$analyses = $connection->createCommand("SELECT * FROM test_to_bid")->queryAll();
            //echo "<pre>";
            //print_r($analysis);
            //echo "</pre>";
            $analysisDataprovider = new ArrayDataProvider([
                'key'=>'analysis_id',
                'allModels' => $temp_table,
                //'pagination'=>false,
                'pagination'=> [
                    'pageSize' => 1,
                ],

            ]);


            $model = new Analysis();

            return $this->render('view1', [
                'model' => $model,
                'analysisdataprovider'=> $analysisDataprovider,
            ]);
        } else {
            return 'zero!';
        }*/
        //exit;

        /*$analysis = $connection->createCommand("
            SELECT s.sample_name, s.sample_code, a.*
            FROM tbl_analysis a
            INNER JOIN tbl_sample s ON a.sample_id = s.sample_id
            INNER JOIN tbl_referral r ON s.referral_id = r.referral_id
            WHERE r.referral_id = 1
            ")->queryAll();*/

        $analysis = Analysis::find()
            ->joinWith('sample',false)
            ->where('tbl_sample.referral_id =:referralId',[':referralId'=>$referralId])
            ->orderBy('sample_id');

        $analysisDataprovider = new ActiveDataProvider([
            'query' => $analysis,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        /*$analysisDataprovider = new ArrayDataProvider([
                'key'=>'analysis_id',
                'allModels' => $analysis,
                'pagination'=>false,
                //'pagination'=> [
                //    'pageSize' => 1,
                //],

            ]);*/
        //session_start();
        $list_testbid = 'test_bids_'.$referralId;
        $subtotal = 0;
        $discounted = 0;
        $total = 0;
        if(!isset($_SESSION[$list_testbid]) || empty($_SESSION[$list_testbid])){
            $testbidDataProvider = new ArrayDataProvider([
                //'key'=>'analysis_id',
                'allModels' => [],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
           // print_r($_SESSION['test_bid']);
        } else {
            //$session = Yii::$app->session;
            $listTestbid = [];
            $sum_fees = [];
            //ksort($_SESSION[$list_testbid]); //sort array key in ascending order so that analysis_id will be sorted
            foreach($_SESSION[$list_testbid] as $testbid){
                //$testmethods = TestnameMethod::model()->findByPk($id);
                $analysis = Analysis::find()->where('analysis_id =:analysisId',[':analysisId'=>$testbid['analysis_id']])->one();
                $raw = array(
                    'sample_id' => $analysis->sample_id,
                    'sample_name' => $analysis->sample->sample_name,
                    //'sample_code' => $analysis->sample->sample_code,
                    'analysis_id'=> $analysis->analysis_id,
                    'test_name' => $analysis->testname->test_name,
                    'method' => $analysis->methodreference->method,
                    'reference' => $analysis->methodreference->reference,
                    'fee' => $testbid['analysis_fee']
                );
                //asort($raw); //sort array key in ascending order so that analysis_id will be sorted
                array_push($listTestbid, $raw);
                array_push($sum_fees, $testbid['analysis_fee']);
            }
            asort($listTestbid);

            //echo "<pre>";
            //print_r($_SESSION);
            //echo "</pre>";
            //echo Yii::app()->session->add('test_bid_'.$referralId, $listTestbid);

            $subtotal = array_sum($sum_fees);
            $discounted = $subtotal * ($referral->discount_rate/100);
            $total = $subtotal - $discounted;

            $testbidDataProvider = new ArrayDataProvider([
                'key'=>'analysis_id',
                'allModels' => $listTestbid,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }

        //if(isset($_SESSION['addbid_requirement_'.$referralId])){
            //echo "<pre>";
            //var_dump($_SESSION['addbid_requirement_'.$referralId]);
            //print_r($_SESSION['test_bids']);
            //echo "</pre>";
            //unset($_SESSION['tests_bidded']);
            //unset($_SESSION['test_bids']);
            //unset($_SESSION['addbid_requirement_'.$referralId]);
        //}

        //$model = new Analysis();


        return $this->render('view1', [
            //'model' => $model,
            'countBid' => $bid,
            'referralId' => $referralId,
            'analysisdataprovider'=> $analysisDataprovider,
            'testbidDataProvider'=> $testbidDataProvider,
            'subtotal' => $subtotal,
            'discounted' => $discounted,
            'total' => $total,
        ]);
    }

    public function actionAddbid_requirement(){
        $model = new Bid();
        if (Yii::$app->request->post()) {
            $bid = Yii::$app->request->post('Bid');
            $referralId = (int) Yii::$app->request->post('referral_id');
            //print_r(Yii::$app->request->post());
            //exit;
            //$referralId = (int) Yii::$app->request->post('referral_id');
            //$analysisId = (int) Yii::$app->request->post('editableKey');
            //$fee = Yii::$app->request->post('analysis_fee');
            //$output = '';
            //$message = '';
            //if(is_numeric($fee) == 1 && $fee > 0){
            //    $output = number_format($fee,2);
            //} else {
            //    $output = "<span style='color:#FF0000;'>Invalid input!</span>";
            //    $message = 'Invalid input';
            //}
            //$out = Json::encode(['output' => $output, 'message' => $message]);
            //echo $out;
            //return;
            if($referralId > 0){
                //$model->referral_id = $referralId;
                //$model->bidder_agency_id = (int) Yii::$app->user->identity->profile->rstl_id;
                //$model->sample_requirements = $bid['sample_requirements'];
                //$model->bid_amount = $bid['bid_amount'];
                //$model->remarks = $bid['remarks'];
                //$model->estimated_due = date('Y-m-d',strtotime($bid['estimated_due']));
                //$model->created_at = date('Y-m-d H:i:s');
                //$model->updated_at = date('Y-m-d H:i:s');
                $session = Yii::$app->session;
                $bid_requirement = 'addbid_requirement_'.$referralId;
                $requirements = $session[$bid_requirement];
                //$testBids[$analysisId] = ['analysis_id'=>$analysisId,'analysis_fee'=> $fee];
                $requirements['bidder_agency_id'] = (int) Yii::$app->user->identity->profile->rstl_id;
                $requirements['sample_requirements'] = $bid['sample_requirements'];
                $requirements['remarks'] = $bid['remarks'];
                $requirements['estimated_due'] = date('Y-m-d',strtotime($bid['estimated_due']));
                $_SESSION[$bid_requirement] = $requirements;


                //if($model->save()){
                //if($model->save()){
                Yii::$app->session->setFlash('success', "Bid requirements added.");
                return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                //} else {
                //    Yii::$app->session->setFlash('error', "Fail to add test bid");
                //    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                //}
            } else {
                return 'Not a valid referral ID!';
            }
        }

        return $this->renderAjax('_form', [
            'model' => $model,
            'referralId' => (int) Yii::$app->request->get('referral_id'),
       ]);
    }

    public function actionUpdatebid_requirement(){
        $model = new Bid();

        if (Yii::$app->request->post()) {
            $bid = Yii::$app->request->post('Bid');
            $referralId = (int) Yii::$app->request->post('referral_id');
            if($referralId > 0){
                $session = Yii::$app->session;
                $bid_requirement = 'addbid_requirement_'.$referralId;
                $requirements = $session[$bid_requirement];
                $requirements['bidder_agency_id'] = (int) Yii::$app->user->identity->profile->rstl_id;
                $requirements['sample_requirements'] = $bid['sample_requirements'];
                $requirements['remarks'] = $bid['remarks'];
                $requirements['estimated_due'] = date('Y-m-d',strtotime($bid['estimated_due']));
                $_SESSION[$bid_requirement] = $requirements;

                Yii::$app->session->setFlash('success', "Bid requirements updated.");
                return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
            } else {
                return 'Not a valid referral ID!';
            }
        }
        $bid_requirement = Yii::$app->session->get('addbid_requirement_'.Yii::$app->request->get('referral_id'));
        $model->estimated_due = $bid_requirement['estimated_due'];
        return $this->renderAjax('_form', [
            'model' => $model,
            'referralId' =>  (int) Yii::$app->request->get('referral_id'),
       ]);
    }

    public function actionViewbid_requirement(){
        //$model = new Bid();
        /*if (Yii::$app->request->post()) {
            $bid = Yii::$app->request->post('Bid');
            $referralId = (int) Yii::$app->request->post('referral_id');
            //print_r(Yii::$app->request->post());
            //exit;
            //$referralId = (int) Yii::$app->request->post('referral_id');
            //$analysisId = (int) Yii::$app->request->post('editableKey');
            //$fee = Yii::$app->request->post('analysis_fee');
            //$output = '';
            //$message = '';
            //if(is_numeric($fee) == 1 && $fee > 0){
            //    $output = number_format($fee,2);
            //} else {
            //    $output = "<span style='color:#FF0000;'>Invalid input!</span>";
            //    $message = 'Invalid input';
            //}
            //$out = Json::encode(['output' => $output, 'message' => $message]);
            //echo $out;
            //return;
            if($referralId > 0){
                $model->referral_id = $referralId;
                $model->bidder_agency_id = (int) Yii::$app->user->identity->profile->rstl_id;
                $model->sample_requirements = $bid['sample_requirements'];
                $model->bid_amount = $bid['bid_amount'];
                $model->remarks = $bid['remarks'];
                $model->estimated_due = $bid['estimated_due'];
                $model->created_at = date('Y-m-d H:i:s');
                $model->updated_at = date('Y-m-d H:i:s');

                if($model->save()){
                    Yii::$app->session->setFlash('success', "Add test bid successful!");
                    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                } else {
                    Yii::$app->session->setFlash('error', "Fail to add test bid");
                    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                }
            } else {
                return 'Not a valid referral ID!';
            }
        }*/
        $agencyId = (int) Yii::$app->user->identity->profile->rstl_id;
        $referralId = (int) Yii::$app->request->get('referral_id');
        if($referralId > 0 && $agencyId > 0){
            $query = Bid::find()->where('referral_id =:referralId AND bidder_agency_id =:agencyId',[':referralId'=>$referralId,':agencyId'=>$agencyId]);
            $count = $query->count();

            if($count > 0){
                $model = $query->one();
            } else {
                $model = new Bid();
            }
        } else {
            Yii::$app->session->setFlash('error', "Not a valid referral!");
            return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
        }

        return $this->renderAjax('_view_requirement', [
            'model' => $model,
            'count' => $count,
            'referralId' => $referralId,
        ]);
    }

    public function actionInserttest_bid()
    {
        //$agencyId = (int) Yii::$app->user->identity->profile->rstl_id;
        //$referralId = (int) Yii::$app->request->get('referral_id');

        //$model = Bid::find()->where('referral_id =:referralId AND bidder_agency_id =:agencyId',[':referralId'=>$referralId,':agencyId'=>$agencyId])->one();

        $analysisId = (int) Yii::$app->request->get('analysis_id');
        $referralId = (int) Yii::$app->request->get('referral_id');

        if($analysisId > 0 && $referralId > 0){
            $model = Analysis::find()->where('analysis_id =:analysisId',[':analysisId'=>$analysisId])->one();

            if (Yii::$app->request->post()) {
                /*$testbid = new Testbid();
                $testbid->referral_id = $referralId;
                $testbid->bidder_agency_id = (int) Yii::$app->user->identity->profile->rstl_id;
                $testbid->sample_requirements = $bid['sample_requirements'];
                $testbid->bid_amount = $bid['bid_amount'];
                $testbid->remarks = $bid['remarks'];
                $testbid->estimated_due = date('Y-m-d',strtotime($bid['estimated_due']));
                $testbid->created_at = date('Y-m-d H:i:s');
                $testbid->updated_at = date('Y-m-d H:i:s');

                if($testbid->save()){
                    Yii::$app->session->setFlash('success', "Add test bid successful!");
                    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                } else {
                    Yii::$app->session->setFlash('error', "Fail to add test bid");
                    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                }*/

                $fee = Yii::$app->request->post('analysis_fee');
                if(is_numeric($fee) == 1 && $fee > 0 && !empty($fee)){
                    //print_r(Yii::$app->request->post());
                    /*$existed = 0;
                    //session_start();
                    if(!isset($_SESSION['test_bid'])){
                       //$_SESSION['test_bid'] = [];
                        $_SESSION['test_bid'] = [1=>['analysis_id'=>$analysisId,'analysis_fee'=>$fee]];
                        //$_SESSION['test_bid'] = [['analysis_id'=>$analysisId,'analysis_fee'=>$fee]];
                    } else {
                        $offset = 0;
                        foreach($_SESSION['test_bid'] as $item) {
                            $offset++;
                            while(list($key, $value) = each($item)) {
                                if($key == "analysis_id" && $value == $analysisId) {
                                    // if the analysis_id is already in the test_bid only fee will be updated
                                    array_splice($_SESSION['test_bid'], $offset-1, 1, [['analysis_id' => $analysisId, 'analysis_fee' => $fee]]);
                                    $existed = 1;
                              } // close if condition
                            } // close while loop
                        } // close foreach loop
                        if($existed == 0){
                            array_push($_SESSION['test_bid'], ['analysis_id'=>$analysisId,'analysis_fee'=>$fee]);
                        }
                    }*/

                    //$session = Yii::$app->session;
                    //if ($session->isActive) {
                    //    echo $session->isActive;
                    //}
                    $session = Yii::$app->session;
                    $list_testbid = 'test_bids_'.$referralId;
                    $testBids = $session[$list_testbid];
                    //$testBids[$analysisId] = $analysisId;
                    $testBids[$analysisId] = ['analysis_id'=>$analysisId,'analysis_fee'=> $fee];
                    $_SESSION[$list_testbid] = $testBids;
                    //$testBids['analysis_ids'] = $analysisId;
                    //$testBids['fees'] = $fee;
                    //$session['test_bids'] = array_push($arr, $testBids);
                    //$testBids['test_bids'] = ['analysis_id'=>$testBids['analysis_ids'],'fee'=> $testBids['fees']];
                    //$testBids['test_bids'] = ['analysis_id'=>$analysisId,'fee'=> $fee];
                   // $list = ['analysis_id'=>$testBids['analysis_ids'],'fee'=> $testBids['fees']];
                    //$session['test_bids'] = $testBids;


                    //$testBids['analyses'] = [['analysis_id'=>$analysisId,'fee'=>$fee]];

                    /*if(!isset($_SESSION['test_bids']) || empty($_SESSION['test_bids'])){
                        $_SESSION['test_bids'] = [];
                    }

                    if(array_key_exists($analysisId, $_SESSION['test_bids'])){
                        $message = 'Method reference already selected.';
                    } else {
                        $message = 'Method reference successfully added.';
                        $testmethod_item = $_SESSION['test_bids'];
                        $testmethod_item[$analysisId] = $analysisId;
                        $_SESSION['test_bids'] = $testmethod_item;
                    }*/

                    //array_push($_SESSION['test_bid'], $analysisId);

                   /* $listTestbid = [];
                    foreach($_SESSION['test_bid'] as $testbid){
                        //$testmethods = TestnameMethod::model()->findByPk($id);
                        $analysis = Analysis::find()->where('analysis_id =:analysisId',[':analysisId'=>$testbid['analysis_id']])->one();
                        $raw = array(
                            'sample_name' => $analysis->sample->sample_name,
                            //'sample_code' => $analysis->sample->sample_code,
                            'analysis_id'=> $analysis->analysis_id,
                            'testname' => $analysis->testname->test_name,
                            'method' => $analysis->methodreference->method,
                            'reference' => $analysis->methodreference->reference,
                            'fee' => $testbid['analysis_fee']
                        );
                        array_push($listTestbid, $raw);
                    }

                    $testbidDataProvider = new ArrayDataProvider([
                        'key'=>'analysis_id',
                        'allModels' => $listTestbid,
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);*/

                    //return $this->renderPartial('_testbid', ['testbidDataProvider'=>$testbidDataProvider]);
                    Yii::$app->session->setFlash('success', 'Adding fee successful!');
                    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);

                } else {
                    //return "<span style='color:#FF0000;'>Please input a valid </span>";
                    Yii::$app->session->setFlash('error', "Please input a valid analysis fee!");
                    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                }
            }

            return $this->renderAjax('_form0', [
                'model' => $model,
            ]);
        } else {
            return "<span style='color:#FF0000;'>Not a valid referral ID</span>";
        }
    }

    public function actionRemove_testbid(){
        $referralId = (int) Yii::$app->request->get('referral_id');
        $analysisId = (int) Yii::$app->request->get('analysis_id');
        $testbidRefId = 'test_bids_'.$referralId;
        $test_bids = Yii::$app->session->get($testbidRefId);

        if($analysisId > 0 && $referralId > 0 && count($test_bids) > 0){
            foreach ($test_bids as $key => $value) {
                if($value["analysis_id"] == $analysisId){
                    unset($_SESSION[$testbidRefId][$key]);
                    if(count($test_bids) == 0 && empty($test_bids)){
                        //unset($_SESSION[$testbidRefId]);
                        Yii::$app->session->remove($testbidRefId);
                    }
                    Yii::$app->session->setFlash('success', 'Successfully removed.');
                    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                } else {
                    Yii::$app->session->setFlash('error', 'Fail to remove!');
                    return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
                }
            }
        } else {
            Yii::$app->session->setFlash('error', 'Not a valid action!');
            return $this->redirect(['/referrals/bid/temporary?referral_id='.$referralId]);
        }

    }

    public function actionUpdate_analysis_fee(){
        $referralId = (int) Yii::$app->request->get('referral_id');
        if (Yii::$app->request->post()) {
            $analysisId = (int) Yii::$app->request->post('editableKey');
            $fee = Yii::$app->request->post('analysis_fee');
            $output = '';
            $message = '';

            if(is_numeric($fee) == 1 && $fee > 0 && !empty($fee)){
                //$analysis_fee = Yii::$app->session->get('test_bids_'.$referralId);
                //$_SESSION['test_bid'] = [['analysis_id'=>$analysisId,'analysis_fee'=>$fee]];
                $session = Yii::$app->session;
                $list_testbid = 'test_bids_'.$referralId;
                $testBids = $session[$list_testbid];
                $testBids[$analysisId] = $analysisId;
                $testBids[$analysisId] = ['analysis_id'=>$analysisId,'analysis_fee'=> $fee];
                $_SESSION[$list_testbid] = $testBids;
                $output = $fee;
            } else {
                $output = "<span style='color:#FF0000;'>Invalid input!</span>";
                $message = 'Invalid input';
            }
            $out = Json::encode(['output' => $output, 'message' => $message]);
            echo $out;
        }

    }

    public function actionInserttest_bid0()
    {
        $model = new Analysis();

        if (Yii::$app->request->post()) {
           // \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

            $referralId = (int) Yii::$app->request->post('referral_id');
            $analysisId = (int) Yii::$app->request->post('editableKey');
            $fee = Yii::$app->request->post('analysis_fee');

            $output = '';
            $message = '';
            if(is_numeric($fee) == 1 && $fee > 0)
            {
                $existed = 0;
                //session_start();
                if(!isset($_SESSION['test_bid'])){
                   //$_SESSION['test_bid'] = [];
                    //$_SESSION['test_bid'] = [1=>['analysis_id'=>$analysisId,'analysis_fee'=>$fee]];
                    $_SESSION['test_bid'] = [['analysis_id'=>$analysisId,'analysis_fee'=>$fee]];
                } else {
                    $offset = 0;
                    foreach($_SESSION['test_bid'] as $item) {
                        $offset++;
                        while(list($key, $value) = each($item)) {
                            if($key == "analysis_id" && $value == $analysisId) {
                                // if the analysis_id is already in the test_bid only fee will be updated
                                array_splice($_SESSION['test_bid'], $offset-1, 1, [['analysis_id' => $analysisId, 'analysis_fee' => $fee]]);
                                $existed = 1;
                          } // close if condition
                        } // close while loop
                    } // close foreach loop
                    if($existed == 0){
                        array_push($_SESSION['test_bid'], ['analysis_id'=>$analysisId,'analysis_fee'=>$fee]);
                    }
                }
                $output = number_format($fee,2);

                //$sampletypeId = implode(',', array_map(function ($data) {
                    //return $data['sampletype_id'];
                //}, $sample));

                //$testbidDataProvider = Analysis::find()->where('referral_id =:referralId',[':referralId'=>$id])->all()

                /*$testbidDataProvider = new ArrayDataProvider([
                    'key'=>'package_id',
                    'allModels' => $listpackage,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);*/
                $listTestbid = [];
                foreach($_SESSION['test_bid'] as $testbid){
                    //$testmethods = TestnameMethod::model()->findByPk($id);
                    $analysis = Analysis::find()->where('analysis_id =:analysisId',[':analysisId'=>$testbid['analysis_id']])->one();
                    $raw = array(
                        'sample_name' => $analysis->sample->sample_name,
                        'sample_code' => $analysis->sample->sample_code,
                        'analysis_id'=> $analysis->analysis_id,
                        'testname' => $analysis->testname->test_name,
                        'method' => $analysis->methodreference->method,
                        'reference' => $analysis->methodreference->reference,
                        'fee' => $testbid['analysis_fee']
                    );
                    array_push($listTestbid, $raw);
                }

                $testbidDataProvider = new ArrayDataProvider([
                    'key'=>'analysis_id',
                    'allModels' => $listTestbid,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);

                return $this->renderPartial('_testbid', ['testbidDataProvider'=>$testbidDataProvider]);
            } else {
                $output = "<span style='color:#FF0000;'>Invalid input!</span>";
                $message = 'Invalid input';
            }
            $out = Json::encode(['output' => $output, 'message' => $message]);
            //print_r($_SESSION['test_bid']);
            //echo $out;
            //return;


            //return $this->renderPartial('_package-details', ['testname_methodDataProvider'=>$testname_methodDataProvider]);

/*
            if($referralId > 0 && $analysisId > 0){
                $referral = $this->findReferral($referralId);
                $connection= Yii::$app->db;
                $connection->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
                $transaction = $connection->beginTransaction();

                $temp_table = $connection->createCommand("
                    CREATE TEMPORARY TABLE test_to_bid
                    SELECT s.sample_name, s.sample_code, a.*
                    FROM tbl_analysis a
                    INNER JOIN tbl_sample s ON a.sample_id = s.sample_id
                    INNER JOIN tbl_referral r ON s.referral_id = r.referral_id
                    WHERE r.referral_id = 1
                ")->execute();


                $insert_temp = Yii::$app->db->createCommand()->update('test_to_bid', ['analysis_fee' => $fee], ['analysis_id'=>$analysisId])->execute();

                if($insert_temp > 0){
                    $transaction->commit();
                    $a = $connection->createCommand("SELECT * FROM test_to_bid")->queryAll();
                    //echo "<pre>";
                    //print_r($a);
                    //echo "<pre>";
                    return 'true';
                } else {
                    return 'false';
                }
            } else {
                //Yii::$app->session->setFlash('error', "Referral ID not valid!");
                //return $this->redirect(['/referrals/notification']);
                return 'false';
            } 

            /*$session = Yii::$app->session;

            $referralId = (int) Yii::$app->request->post('referral_id');
            $analysisId = (int) Yii::$app->request->post('analysis_id');
            $fee = Yii::$app->request->post('analysis_fee');

            if(isset($_POST['test_methods'])){
                $testnameId = (int) $_POST['test_methods'];
            }

            if(isset($_POST['testmethodId'])){
                $testmethodrefId = (int) $_POST['testmethodId'];
            }
            //unset($_SESSION['testmethods']);
            if(!isset($_SESSION['testmethods'])){
                $_SESSION['testmethods'] = array();
            }

            // open a session
            //$session->open();

            if(!isset($session['test_bid'])) {
                //$session->get('language')
                $session->set('test_bid', []);
            }

            if(array_key_exists($testmethodrefId, $_SESSION['testmethods'])){
                $message = 'Method reference already selected.';
            } else {
                $message = 'Method reference successfully added.';
                $testmethod_item = $_SESSION['testmethods'];
                $testmethod_item[$testmethodrefId] = $testmethodrefId;
                $_SESSION['testmethods'] = $testmethod_item;
            }

            if(isset($_POST['testmethodId']) && Yii::app()->request->isAjaxRequest){
                $listTests = array();
                foreach($_SESSION['testmethods'] as $testmethod=>$id){
                    $testmethods = TestnameMethod::model()->findByPk($id);
                    $raw = array(

                        'id'=>$testmethods->id,
                        'testname'=>$testmethods->testname->testName,
                        'method'=>$testmethods->method->method,
                        'reference'=>$testmethods->method->reference,
                        'fee'=>Yii::app()->format->formatNumber($testmethods->method->fee)
                    );
                    
                    array_push($listTests, $raw);
                }
            }
            
            $testmethodDataProvider = new CArrayDataProvider($listTests, array('pagination'=>false));
            echo $this->renderPartial('_methodrefs',array('testmethodDataProvider'=>$testmethodDataProvider,'testMethodSession'=>$testMethodSession),true);
        */
        }

        //if(Yii::$app->request->isAjax){
       //     return $this->renderAjax('_form0', [
       //         'model' => $model,
       //     ]);
            //return $this->renderAjax('_form0');
        //}
    }

    protected function is_decimal($fee){
        $amount = trim($fee); // trim space keys
        $amount = is_numeric($amount); // validate numeric and numeric string, e.g., 12.00, 1e00, 123; but not -123
        $amount = preg_match('/^\d$/', $amount); // only allow any digit e.g., 0,1,2,3,4,5,6,7,8,9. This will eliminate the numeric string, e.g., 1e00
        $amount = round($amount, 2); // to a specified number of decimal places.e.g., 1.12345=> 1.12

        return (int) $amount;
    }
}
