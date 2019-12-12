<?php

namespace frontend\modules\pstc\controllers;

use Yii;
use common\models\referral\Pstcrequest;
use common\models\referral\Pstcsample;
use common\models\referral\Pstcanalysis;
use common\models\referral\PstcrequestSearch;
use common\models\referral\Pstcattachment;
use common\models\referral\Customer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\components\PstcFunctions;
use yii\db\Query;

/**
 * PstcrequestController implements the CRUD actions for Pstcrequest model.
 */
class PstcrequestController extends Controller
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
     * Lists all Pstcrequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(isset(Yii::$app->user->identity->profile->rstl_id)) {
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
            $pstcId = (int) Yii::$app->user->identity->profile->pstc_id;

            if($pstcId > 0) {
                $searchModel = new PstcrequestSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                $customers = $this->listCustomers($rstlId);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'customers' => $customers,
                ]);
            } else {
                Yii::$app->session->setFlash('error', "You don't have a valid PSTC ID!");
                return $this->redirect(['/pstc/customer']);
            }
        } else {
            return $this->redirect(['/site/login']);
        }
    }

    /**
     * Displays a single Pstcrequest model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // return $this->render('view', [
        //     'model' => $this->findModel($id),
        // ]);
        
        if(isset(Yii::$app->user->identity->profile->rstl_id)){
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
        } else {
            //return 'Session time out!';
            return $this->redirect(['/site/login']);
        }

        if($rstlId > 0)
        {
            $function = new PstcFunctions();

            $pstcId = (int) Yii::$app->user->identity->profile->pstc_id;
            
            // $checknotified = Notification::find()
            //     ->where('referral_id =:referralId', [':referralId'=>$id])
            //     ->andWhere('recipient_id =:recipientId', [':recipientId'=>$rstlId])
            //     ->andWhere(['in', 'notification_type_id', [1,3]])
            //     ->count();
                
            $checkOwner = $function->checkOwner($id,$rstlId,$pstcId);

            if($checkOwner > 0)
            {
                $model = $this->findModel($id);
                $samples = Pstcsample::find()->where('pstc_request_id =:requestId',[':requestId'=>$id]);
                $analyses = Pstcanalysis::find()->joinWith(['sample'],false)->where('pstc_request_id =:requestId',[':requestId'=>$id]);
                $attachments = Pstcattachment::find()->where('pstc_request_id =:requestId',[':requestId'=>$id]);
                // $analyses = (new \yii\db\Query())->select('tbl_pstcanalysis.*, tbl_pstcsample.*,tbl_testname.testName, tbl_methodreference.method,tbl_methodreference.reference,tbl_methodreference.fee')->from('tbl_pstcanalysis')
                //    ->join('LEFT JOIN', 'tbl_pstcsample', 'tbl_pstcanalysis.pstc_sample_id = tbl_pstcsample.pstc_sample_id')
                //    ->join('LEFT JOIN', 'eulims_lab.`tbl_testname`', 'tbl_pstcanalysis.testname_id = tbl_testname.testname_id')
                //    ->join('LEFT JOIN', 'eulims_lab.`tbl_methodreference`', 'tbl_pstcanalysis.method_id = tbl_methodreference.method_reference_id')
                //    ->where('pstc_request_id =:requestId',[':requestId'=>$id])
                //    ->all();

                /*$analyses = Yii::$app->db->createCommand("SELECT `tbl_pstcanalysis`.*, `tbl_pstcsample`.*, `tbl_testname`.`testName`, `tbl_methodreference`.`method`, `tbl_methodreference`.`reference`, `tbl_methodreference`.`fee` FROM `tbl_pstcanalysis` LEFT JOIN `tbl_pstcsample` ON tbl_pstcanalysis.pstc_sample_id = tbl_pstcsample.pstc_sample_id LEFT JOIN `eulims_lab`.`tbl_testname` ON tbl_pstcanalysis.testname_id = tbl_testname.testname_id LEFT JOIN `eulims_lab`.`tbl_methodreference` ON tbl_pstcanalysis.method_id = tbl_methodreference.method_reference_id WHERE pstc_request_id ='1'")->queryAll();*/

                // echo '<pre>';
                // print_r($analyses);
                // echo '</pre>';
                // exit;

                $sampleDataProvider = new ActiveDataProvider([
                    'query' => $samples,
                    'pagination' => false,
                    /*'pagination' => [
                        'pageSize' => 10,
                    ],*/
                ]);

                //echo '<pre>';
                //print_r($analyses->all());
                //echo '</pre>';
                //exit;

                $analysisDataprovider = new ActiveDataProvider([
                    'query' => $analyses,
                    'pagination' => false,
                ]);

                // $analysisDataprovider = new ArrayDataProvider([
                //     //'key'=>'testname_method_id',
                //     'allModels' => $analyses,
                //     'pagination'=>false,
                // ]);


                // echo "<pre>";
                // print_r($analysisDataprovider);
                // echo "</pre>";
                // exit;

                $attachmentDataprovider = new ActiveDataProvider([
                    'query' => $attachments,
                    'pagination' => false,
                ]);

               
                $query = new Query;
                $subtotal = $query->from('tbl_pstcanalysis')
                   ->join('INNER JOIN', 'tbl_pstcsample', 'tbl_pstcanalysis.pstc_sample_id = tbl_pstcsample.pstc_sample_id')
                   ->where('pstc_request_id =:requestId',[':requestId'=>$id])
                   ->sum('fee');

                /*$subtotal = Analysis::find()->joinWith('sample',false)
                    ->where('referral_id =:referralId',[':referralId'=>$id])
                    ->sum('analysis_fee');*/

                $rate = $model->discount_rate;
                
                $discounted = $subtotal * ($rate/100);
                $total = $subtotal - $discounted;
                 
                return $this->render('view', [
                    'model' => $model,
                    //'request' => $request,
                    //'customer' => $customer,
                    'sampleDataProvider' => $sampleDataProvider,
                    'analysisDataprovider'=> $analysisDataprovider,
                    'attachmentDataprovider' => $attachmentDataprovider,
                    'subtotal' => $subtotal,
                    'discounted' => $discounted,
                    'total' => $total,
                    'countSample' => $samples->count(),
                ]);
            } else {
                Yii::$app->session->setFlash('error', "Denied access!");
                return $this->redirect(['/pstc/pstcrequest']);
            }
        } else {
            Yii::$app->session->setFlash('error', "Invalid request!");
            return $this->redirect(['/pstc/pstcrequest']);
        }
    }

    /**
     * Creates a new Pstcrequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pstcrequest();
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

        //if ($model->load(Yii::$app->request->post())) {
        //&& $model->save()


       if($rstlId > 0){
            //$model->rstl_id = $rstlId;
            //$model->user_id = (int) Yii::$app->user->identity->profile->user_id;
            $mi = !empty(Yii::$app->user->identity->profile->middleinitial) ? " ".substr(Yii::$app->user->identity->profile->middleinitial, 0, 1).". " : " ";
            $user_fullname = Yii::$app->user->identity->profile->firstname.$mi.Yii::$app->user->identity->profile->lastname;

            $customers = $this->listCustomers($rstlId);
            
            if($user_fullname){
                $model->received_by = $user_fullname;
            } else {
                $model->received_by = "";
            }
        } else {
            return $this->redirect(['/site/login']);
        }

        if(Yii::$app->request->post()) {
            $post = Yii::$app->request->post('Pstcrequest');

            $model->rstl_id = $rstlId;
            $model->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
            //$model->pstc_id = 113;
            $model->customer_id = (int) $post['customer_id'];
            $model->submitted_by = $post['submitted_by'];
            $model->received_by = $user_fullname;
            $model->user_id = (int) Yii::$app->user->identity->profile->user_id;
            $model->status_id = 1;
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if($model->save()){
                Yii::$app->session->setFlash('success', 'PSTC Request Successfully Created!');
                return $this->redirect(['view', 'id' => $model->pstc_request_id]);
                //return $this->redirect(['/pstc/pstcrequest']);
            } else {
                Yii::$app->session->setFlash('error', 'PSTC Request failed to create!');
                return $this->redirect(['/pstc/pstcrequest']);
            }
        } else {
            if(\Yii::$app->request->isAjax){
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'customers' => $customers,
                ]);
            } else {
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'customers' => $customers,
                ]);
            }
        }
    }

    public function actionRevertlocal($request_id) 
    {
        
        $model = $this->findModel($request_id);

        /*$data = (new \yii\db\Query())
            ->from('eulims_referral_lab.tbl_pstcrequest')
            ->join('INNER JOIN', 'eulims_referral_lab.tbl_pstcsample', 'tbl_pstcrequest.pstc_request_id = tbl_pstcsample.pstc_request_id')
            ->join('INNER JOIN', 'eulims_referral_lab.tbl_pstcanalysis', 'tbl_pstcsample.pstc_sample_id = tbl_pstcanalysis.pstc_sample_id')
            ->where('tbl_pstcrequest.pstc_request_id =:requestId',[':requestId'=>$request_id])
            ->orderBy('tbl_sampletypetestname.testname_id')
            //->asArray()
            ->all(); */

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        $query1 = "UPDATE `tbl_pstcrequest` r
            LEFT JOIN `tbl_pstcsample` s ON r.`pstc_request_id` = s.`pstc_request_id`
            LEFT JOIN `tbl_pstcanalysis` a ON s.`pstc_sample_id` = a.`pstc_sample_id`
            SET r.`is_referral` = 0, r.`sample_received_date` = NULL, s.`is_referral` = 0, s.`testcategory_id` = NULL, s.`sampletype_id` = NULL, a.`analysis_offered` = 1
            WHERE r.`pstc_request_id`=:requestId";

        $query2 = "DELETE `tbl_pstcanalysis` FROM `tbl_pstcsample`
            INNER JOIN `tbl_pstcanalysis` ON `tbl_pstcsample`.`pstc_sample_id` = `tbl_pstcanalysis`.`pstc_sample_id`
            WHERE `tbl_pstcsample`.`pstc_request_id` =:requestId";

        $params = [':requestId' => $request_id];
        $command1 = $connection->createCommand($query1);
        $command1->bindValues($params);
        $execute1 = $command1->execute();

        if($execute1 > 0) {
            $command2 = $connection->createCommand($query2);
            $command2->bindValues($params);
            $command2->execute();

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Request Successfully Updated!');
            return $this->redirect(['view', 'id' => $model->pstc_request_id]);
        } else {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Failed to revert to local request!');
            return $this->redirect(['view', 'id' => $model->pstc_request_id]);
        }
    }

    public function actionMarkreferral($request_id) 
    {
        
        $model = $this->findModel($request_id);

        /*$data = (new \yii\db\Query())
            ->from('eulims_referral_lab.tbl_pstcrequest')
            ->join('INNER JOIN', 'eulims_referral_lab.tbl_pstcsample', 'tbl_pstcrequest.pstc_request_id = tbl_pstcsample.pstc_request_id')
            ->join('INNER JOIN', 'eulims_referral_lab.tbl_pstcanalysis', 'tbl_pstcsample.pstc_sample_id = tbl_pstcanalysis.pstc_sample_id')
            ->where('tbl_pstcrequest.pstc_request_id =:requestId',[':requestId'=>$request_id])
            ->orderBy('tbl_sampletypetestname.testname_id')
            //->asArray()
            ->all(); */

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        $query = "UPDATE `tbl_pstcrequest` r
            LEFT JOIN `tbl_pstcsample` s ON r.`pstc_request_id` = s.`pstc_request_id`
            LEFT JOIN `tbl_pstcanalysis` a ON s.`pstc_sample_id` = a.`pstc_sample_id`
            SET r.`is_referral` = 1, s.`is_referral` = 1, s.`testcategory_id` = NULL, s.`sampletype_id` = NULL, a.`analysis_offered` = 0
            WHERE r.`pstc_request_id`=:requestId";

        $query2 = "DELETE `tbl_pstcanalysis` FROM `tbl_pstcsample`
            INNER JOIN `tbl_pstcanalysis` ON `tbl_pstcsample`.`pstc_sample_id` = `tbl_pstcanalysis`.`pstc_sample_id`
            WHERE `tbl_pstcsample`.`pstc_request_id` =:requestId";

        //$dateToday = date('Y-m-d H:i:s'); //sample received date default today
        $params = [':requestId' => $request_id];
        $command = $connection->createCommand($query);
        $command->bindValues($params);
        $execute = $command->execute();

        if($execute > 0) {
            $command2 = $connection->createCommand($query2);
            $command2->bindParam(':requestId', $request_id);
            $command2->execute();

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Request Successfully Updated!');
            return $this->redirect(['view', 'id' => $model->pstc_request_id]);
        } else {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Failed to revert to local request!');
            return $this->redirect(['view', 'id' => $model->pstc_request_id]);
        }
    }

    public function actionSample_received_date($request_id)
    {

        if(isset(Yii::$app->user->identity->profile->rstl_id)) {
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
            $pstcId = (int) Yii::$app->user->identity->profile->pstc_id;
            $model = Pstcrequest::find()->where('pstc_request_id =:requestId AND rstl_id =:rstlId AND pstc_id =:pstcId AND is_referral=1',[':requestId'=>$request_id,':rstlId'=>$rstlId,':pstcId'=>$pstcId])->one();

            if(Yii::$app->request->post()) {
                $post = Yii::$app->request->post('Pstcrequest');
                $model->sample_received_date = $post['sample_received_date'];

                if($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Date has been added!');
                    return $this->redirect(['view', 'id' => $request_id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to add date!');
                    return $this->redirect(['view', 'id' => $request_id]);
                }
            } else {
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_sample_received_date', [
                        'model' => $model,
                    ]);
                } else {
                    return $this->renderAjax('_sample_received_date', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            return $this->redirect(['/site/login']);
        }
    }

    /**
     * Updates an existing Pstcrequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

        if($rstlId > 0) {
            $mi = !empty(Yii::$app->user->identity->profile->middleinitial) ? " ".substr(Yii::$app->user->identity->profile->middleinitial, 0, 1).". " : " ";
            $user_fullname = Yii::$app->user->identity->profile->firstname.$mi.Yii::$app->user->identity->profile->lastname;

            $customers = $this->listCustomers($rstlId);
            
            if($user_fullname){
                $model->received_by = $user_fullname;
            } else {
                $model->received_by = "";
            }
        } else {
            return $this->redirect(['/site/login']);
        }

        if(Yii::$app->request->post()) {
            $post = Yii::$app->request->post('Pstcrequest');

            $model->rstl_id = $rstlId;
            $model->pstc_id = (int) Yii::$app->user->identity->profile->pstc_id;
            $model->customer_id = (int) $post['customer_id'];
            $model->submitted_by = $post['submitted_by'];
            $model->received_by = $user_fullname;
            $model->user_id = (int) Yii::$app->user->identity->profile->user_id;
            $model->status_id = 1;
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if($model->save()) {
                Yii::$app->session->setFlash('success', 'PSTC Request Successfully Updated!');
                return $this->redirect(['view', 'id' => $model->pstc_request_id]);
                //return $this->redirect(['/pstc/pstcrequest']);
            } else {
                Yii::$app->session->setFlash('error', 'PSTC Request failed to update!');
                return $this->redirect(['/pstc/pstcrequest']);
            }

        } else {
            if(\Yii::$app->request->isAjax){
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'customers' => $customers,
                ]);
            } else {
                return $this->renderAjax('_form', [
                    'model' => $model,
                    'customers' => $customers,
                ]);
            }
        }

        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //    return $this->redirect(['view', 'id' => $model->pstc_request_id]);
        //}

        //return $this->render('update', [
        //    'model' => $model,
        //]);
    }

    /**
     * Deletes an existing Pstcrequest model.
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
     * Finds the Pstcrequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pstcrequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pstcrequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function listCustomers($rstlId)
    {
        $customer = ArrayHelper::map(Customer::find()->where('rstl_id =:rstlId',[':rstlId'=>$rstlId])->all(), 'customer_id',
            function($customer, $defaultValue) {
                return $customer->customer_name;
        });
        return $customer;
    }
}
