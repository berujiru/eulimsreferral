<?php
namespace frontend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use common\models\system\Profile;
use kartik\mpdf\Pdf;
use yii\helpers\Json;
use common\models\lab\CustomerMigration;
use common\models\lab\RequestMigration;
use common\models\lab\SampleMigration;
use common\models\lab\AnalysisMigration;
use common\models\lab\Customer;
use common\models\finance\CheckMigration;
use common\models\finance\ReceiptMigration;
use common\models\finance\PaymentitemMigration;
use common\models\finance\OrderofpaymentMigration;
use common\models\finance\DepositMigration;
use common\models\finance\CollectionMigration;

use common\models\finance\Restore_op;
use common\models\finance\Restore_paymentitem;
use common\models\finance\Restore_deposit;
use common\models\finance\Restore_receipt;
use common\models\finance\Restore_check;

use common\models\finance\Deposit;

/* @property Customer $customer */
class ApiController extends ActiveController
{
    public $modelClass = 'common\models\system\Profile';
    
    public function verbs() {
        parent::verbs();
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    public function actions() {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = function($action) {
            return new \yii\data\ActiveDataProvider([
                'query' => Profile::find()->where(['user_id' => Yii::$app->user->id]),
            ]);
        };
        return $actions;
    }

   
      public function actionSync_orderofpayment_yii2(){
          
        $post = Yii::$app->request->post(); //get the post
        $data = Yii::$app->request->post('data');
        
        $ctr = 0;
        //$idsave=[];
        $idfirst="";
        $idsave="";
        $idfail="";
        $listIds = [];
        foreach ($data as $op) {
           // $newOp = new Restore_op();
            $newOp= new OrderofpaymentMigration();
            $newOp->rstl_id= $op['rstl_id'];

            $newOp->transactionnum= $op['transactionnum'];
            $newOp->collectiontype_id= $op['collectiontype_id'];
            $newOp->payment_mode_id= $op['payment_mode_id'];
            $newOp->on_account= $op['on_account'];
            $newOp->order_date= $op['order_date'];
            $newOp->customer_id= $op['customer_id'];
            $newOp->receipt_id=$op['receipt_id'];
            $newOp->purpose= $op['purpose'];
            $newOp->payment_status_id= $op['payment_status_id'];
            $newOp->local_orderofpayment_id=$op['orderofpayment_id'];
            if($newOp->save()){
                foreach ($op['payment_item'] as $paymentitem) {
                   // $newPaymentitem = new Restore_paymentitem();
                    $newPaymentitem = new PaymentitemMigration();
                    //$newPaymentitem->paymentitem_id= $item['local_paymentitem_id'];
                    $newPaymentitem->rstl_id=$paymentitem['rstl_id'];
                    $newPaymentitem->request_id=$paymentitem['request_id'];
                    $newPaymentitem->request_type_id=$paymentitem['request_type_id'];
                    $newPaymentitem->orderofpayment_id=$newOp->orderofpayment_id;
                    $newPaymentitem->local_orderofpayment_id=$paymentitem['orderofpayment_id'];
                    $newPaymentitem->details=$paymentitem['details'];
                    $newPaymentitem->amount=$paymentitem['amount'];
                    $newPaymentitem->cancelled=$paymentitem['cancelled'];
                    $newPaymentitem->status=$paymentitem['status'];
                    $newPaymentitem->receipt_id=$paymentitem['receipt_id'];
                    $newPaymentitem->local_paymentitem_id= $paymentitem['paymentitem_id'];
                    $newPaymentitem->save();
                }
                if($ctr == 0){
                    $idfirst=$op['orderofpayment_id'];
                }
                
                $ctr++;
               // array_push($idsave, $op['orderofpayment_id']);
                $idsave=$op['orderofpayment_id'];
            }
            else{
                $idfail=$op['orderofpayment_id'];
            }
        }
        $detail=[
            'num'=>$ctr,
            'idfirst'=>$idfirst,
            'idsave'=>$idsave,
            'idfail'=>$idfail
                
        ];
        array_push($listIds, $detail);
        
        return $listIds;
      }
      
       public function actionSync_orderofpaymentx_yii2(){
          
        $post = Yii::$app->request->post(); //get the post
        $data = Yii::$app->request->post('data');
        
        $ctr = 0;
        //$idsave=[];
        $idsave="";
        $idfail="";
        $listIds = [];
        foreach ($data as $op) {
            $newOp = new OrderofpaymentMigration();
            $newOp->rstl_id= $op['rstl_id'];

            $newOp->transactionnum= $op['transactionnum'];
            $newOp->collectiontype_id= $op['collectiontype_id'];
            $newOp->payment_mode_id= $op['payment_mode_id'];
            $newOp->on_account= $op['on_account'];
            $newOp->order_date= $op['order_date'];
            $newOp->customer_id= $op['customer_id'];
            $newOp->receipt_id=$op['receipt_id'];
            $newOp->purpose= $op['purpose'];
            $newOp->payment_status_id= $op['payment_status_id'];
            $newOp->local_orderofpayment_id=$op['orderofpayment_id'];
            if($newOp->save()){
                $ctr++;
               // array_push($idsave, $op['orderofpayment_id']);
                $idsave=$op['orderofpayment_id'];
            }
            else{
                $idfail=$op['orderofpayment_id'];
            }
        }
        $detail=[
            'num'=>$ctr,
            'idsave'=>$idsave,
            'idfail'=>$idfail
                
        ];
        array_push($listIds, $detail);
        
        return $listIds;
      }
      
      public function actionSync_receipt_yii2(){
          
        $post = Yii::$app->request->post(); //get the post
        $data = Yii::$app->request->post('data');
        
        $ctr = 0;
        //$idsave=[];
        $idsave="";
        $idfail="";
        $idsaveop="";
        $idfailop="";
        $listIds = [];
        foreach ($data as $receipt) {
            //$newReceipt = new Restore_receipt();
            $newReceipt = new ReceiptMigration();
            ////////-----------------------------------------///////////////////////
            $newReceipt->rstl_id=$receipt['rstl_id'];
            $newReceipt->orderofpayment_id=$receipt['local_orderofpayment_id'];
            $newReceipt->deposit_type_id=$receipt['deposit_type_id'];
            $newReceipt->or_series_id=$receipt['or_series_id'];
            $newReceipt->or_number=$receipt['or_number'];
            $newReceipt->receiptDate=$receipt['receiptDate'];
            $newReceipt->payment_mode_id=$receipt['payment_mode_id'];
            $newReceipt->payor=$receipt['payor'];
            $newReceipt->collectiontype_id=$receipt['collectiontype_id'];
            $newReceipt->total=$receipt['total'];
            $newReceipt->deposit_id=$receipt['local_deposit_id'];
            $newReceipt->customer_id=$receipt['customer_id'];
            $newReceipt->terminal_id=1;
            $newReceipt->cancelled=0;
            $newReceipt->local_receipt_id=$receipt['local_receipt_id'];

            ///////////////----------------------------------
            if($newReceipt->save()){
                foreach ($receipt['check'] as $item) {
                    //$newCheck = new Restore_check();
                     $newCheck = new CheckMigration();
                     $newCheck->receipt_id= $newReceipt->receipt_id;
                     $newCheck->local_receipt_id=$receipt['local_receipt_id'];
                     $newCheck->local_check_id=$item['local_check_id'];
                     $newCheck->bank=$item['bank'];
                     $newCheck->checknumber=$item['checknumber'];
                     $newCheck->checkdate=$item['checkdate'];
                     $newCheck->amount=$item['amount'];
                     $newCheck->save(false); 
                }
                 foreach ($receipt['op'] as $op) {
                    // $newOp = new Restore_op();
                     $newOp= new OrderofpaymentMigration();
                     $newOp->rstl_id= $op['rstl_id'];

                     $newOp->transactionnum= $op['transactionnum'];
                     $newOp->collectiontype_id= $op['collectiontype_id'];
                     $newOp->payment_mode_id= $op['payment_mode_id'];
                     $newOp->on_account= $op['on_account'];
                     $newOp->order_date= $op['order_date'];
                     $newOp->customer_id= $op['customer_id'];
                     //$newOp->receipt_id=$op['receipt_id'];
                     $newOp->receipt_id=$newReceipt->receipt_id;
                     $newOp->purpose= $op['purpose'];
                     $newOp->payment_status_id= $op['payment_status_id'];
                     $newOp->local_orderofpayment_id=$op['orderofpayment_id'];
                     if($newOp->save()){
                         foreach ($op['payment_item'] as $paymentitem) {
                            // $newPaymentitem = new Restore_paymentitem();
                             $newPaymentitem = new PaymentitemMigration();
                             //$newPaymentitem->paymentitem_id= $item['local_paymentitem_id'];
                             $newPaymentitem->rstl_id=$paymentitem['rstl_id'];
                             $newPaymentitem->request_id=$paymentitem['request_id'];
                             $newPaymentitem->request_type_id=$paymentitem['request_type_id'];
                             $newPaymentitem->orderofpayment_id=$newOp->orderofpayment_id;
                             $newPaymentitem->local_orderofpayment_id=$paymentitem['orderofpayment_id'];
                             $newPaymentitem->details=$paymentitem['details'];
                             $newPaymentitem->amount=$paymentitem['amount'];
                             $newPaymentitem->cancelled=$paymentitem['cancelled'];
                             $newPaymentitem->status=$paymentitem['status'];
                             //$newPaymentitem->receipt_id=$paymentitem['receipt_id'];
                             $newPaymentitem->receipt_id=$newReceipt->receipt_id;
                             $newPaymentitem->local_paymentitem_id= $paymentitem['paymentitem_id'];
                             $newPaymentitem->save();
                         }
                   
                     }
                     
                 }
               
                $idsave=$receipt['local_receipt_id'];
            }
            else{
                $idfail=$receipt['local_receipt_id'];
            }
        }
        $detail=[
            'num'=>$ctr,
            'idsave'=>$idsave,
            'idfail'=>$idfail
                
        ];
        array_push($listIds, $detail);
        
        return $listIds;
      }

     public function actionSync_deposit_yii2(){
          
        $post = Yii::$app->request->post(); //get the post
        $data = Yii::$app->request->post('data');
        
        $ctr = 0;
        $idsave="";
        $idfail="";
        $listIds = [];
        foreach ($data as $item) {
           // $newDeposit = new Restore_deposit();
            $newDeposit = new DepositMigration();
            $newDeposit->local_deposit_id= $item['local_deposit_id'];
            $newDeposit->rstl_id= $item['rstl_id'];
            $newDeposit->or_series_id= $item['or_series_id'];
            $newDeposit->start_or= $item['start_or'];
            $newDeposit->end_or= $item['end_or'];
            $newDeposit->amount= $item['amount'];
            $newDeposit->deposit_type_id= $item['deposit_type_id'];
            $newDeposit->deposit_date= $item['deposit_date'];
            if($newDeposit->save()){
                $ctr++;
               // array_push($idsave, $op['orderofpayment_id']);
                $idsave=$item['local_deposit_id'];
            }
            else{
                $idfail=$item['local_deposit_id'];
            }
        }
        $detail=[
            'num'=>$ctr,
            'idsave'=>$idsave,
            'idfail'=>$idfail
                
        ];
        array_push($listIds, $detail);
        
        return $listIds;
      }
      
      public function actionSync_paymentitem_yii2(){
          
        $post = Yii::$app->request->post(); //get the post
        $data = Yii::$app->request->post('data');
        
        $ctr = 0;
        $idsave="";
        $idfail="";
        $listIds = [];
        foreach ($data as $paymentitem) {
           //$newPaymentitem = new Restore_paymentitem();
            $newPaymentitem = new PaymentitemMigration();
            //$newPaymentitem->paymentitem_id= $item['local_paymentitem_id'];
            $newPaymentitem->rstl_id=$paymentitem['rstl_id'];
            $newPaymentitem->request_id=$paymentitem['request_id'];
            $newPaymentitem->request_type_id=$paymentitem['request_type_id'];
            $newPaymentitem->orderofpayment_id=$paymentitem['orderofpayment_id'];
            $newPaymentitem->local_orderofpayment_id=$paymentitem['orderofpayment_id'];
            $newPaymentitem->details=$paymentitem['details'];
            $newPaymentitem->amount=$paymentitem['amount'];
            $newPaymentitem->cancelled=$paymentitem['cancelled'];
            $newPaymentitem->status=$paymentitem['status'];
            $newPaymentitem->receipt_id=$paymentitem['receipt_id'];
            $newPaymentitem->local_paymentitem_id= $paymentitem['paymentitem_id'];
            
            if($newPaymentitem->save(false)){
                $ctr++;
               // array_push($idsave, $op['orderofpayment_id']);
                $idsave=$paymentitem['paymentitem_id'];
            }
            else{
                $idfail=$paymentitem['paymentitem_id'];
            }
        }
        $detail=[
            'num'=>$ctr,
            'idsave'=>$idsave,
            'idfail'=>$idfail
                
        ];
        array_push($listIds, $detail);
        
        return $listIds;
      }

      public function actionSync_customer(){ 
        $post = Yii::$app->request->post();
        $ctr = 0;
        if(isset($post)){
            $myvar = Json::decode($post['data']);
            $ids="";
            foreach ($myvar as $var) {
                $newCustomer = new CustomerMigration();
                $newCustomer->rstl_id=$var['rstl_id'];
                $newCustomer->customer_name=$var['customerName'];
                $newCustomer->customer_code=$var['rstl_id']."-".$var['id'];
                $newCustomer->classification_id=$var['classification_id'];
                $newCustomer->latitude=$var['latitude'];
                $newCustomer->longitude=$var['longitude'];
                $newCustomer->head=$var['head'];
                $newCustomer->barangay_id=$var['barangay_id'];
                $newCustomer->address=$var['address'];
                $newCustomer->tel=$var['tel'];
                $newCustomer->fax=$var['fax'];
                $newCustomer->email=$var['email'];
                $newCustomer->customer_type_id=$var['typeId'];
                $newCustomer->business_nature_id=$var['natureId'];
                $newCustomer->industrytype_id=$var['industryId'];
                $newCustomer->created_at=strtotime($var['created']);
                $newCustomer->customer_old_id=$var['id'];
                $newCustomer->Oldcolumn_municipalitycity_id=$var['municipalitycity_id'];
                $newCustomer->Oldcolumn_district=$var['district'];
                if($newCustomer->save(true)){
                }else{
                    $ids=$ids.$var['id'].',';
                }
                $ctr++;
            }
           
        }
         \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
         // echo $ctr;
         return [
            'num'=>$ctr,
            'ids'=>$ids
         ];   
    }
    public function actionSync_request(){ 
        $post = Yii::$app->request->post();
        $ctr = 0;
        if(isset($post)){
            $myvar = Json::decode($post['data']);
            $ids="";
            foreach ($myvar as $var) {
                $newRequest = new RequestMigration();
                $newRequest->request_ref_num=$var['request_ref_num'];
                $newRequest->request_datetime=$var['request_datetime'];
                $newRequest->rstl_id=$var['rstl_id'];
                $newRequest->lab_id=$var['lab_id'];
                $newRequest->customer_id=$var['customer_id'];
                $newRequest->payment_type_id=$var['payment_type_id'];
                $newRequest->modeofrelease_ids=$var['modeofrelease_ids'];
                $newRequest->discount=$var['discount'];
                $newRequest->purpose_id=$var['purpose_id'];
                $newRequest->conforme=$var['conforme'];
                $newRequest->report_due=$var['report_due'];
                $newRequest->total=$var['total'];
                $newRequest->receivedBy =$var['receivedBy'];
                $newRequest->oldColumn_requestId=$var['oldColumn_requestId'];
                $newRequest->oldColumn_sublabId=$var['oldColumn_sublabId'];
                $newRequest->oldColumn_orId=$var['oldColumn_orId'];
                $newRequest->oldColumn_completed=$var['oldColumn_completed'];
                $newRequest->oldColumn_cancelled=$var['oldColumn_cancelled'];
                $newRequest->oldColumn_create_time=$var['oldColumn_create_time'];
                $newRequest->request_old_id=$var['request_old_id'];
                $newRequest->created_at=$var['created_at'];
                $newRequest->discount_id=$var['discount_id'];
                $newRequest->customer_old_id=$var['customer_old_id'];
                if($newRequest->save(true)){
                   
                }else{
                    $ids=$ids.$var['request_old_id'].',';
                }
                $ctr++;
            }
        }
         \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
         // echo $ctr;
         return [
            'num'=>$ctr,
            'ids'=>$ids
         ];   
    }
    public function actionSync_sample(){ 
        
        $post = Yii::$app->request->post();
        $ctr = 0;
        if(isset($post)){
            $myvar = Json::decode($post['data']);
            $ids="";
            foreach ($myvar as $var) {
                $newSample = new SampleMigration();
                $newSample->rstl_id=$var['rstl_id'];
                $newSample->pstcsample_id=$var['pstcsample_id'];
                $newSample->sampletype_id=$var['sample_type_id'];
                $newSample->sample_code=$var['sample_code'];
                $newSample->samplename=$var['samplename'];
                $newSample->description=$var['description'];
                $newSample->sampling_date=$var['sampling_date'];
                $newSample->remarks=$var['remarks'];
                $newSample->request_id=$var['request_id'];
                $newSample->sample_month=$var['sample_month'];
                $newSample->sample_year=$var['sample_year'];
                $newSample->active=$var['active'];
                $newSample->sample_old_id=$var['sample_old_id'];
                $newSample->oldColumn_requestId=$var['oldColumn_requestId'];
                $newSample->oldColumn_completed=$var['oldColumn_completed'];
                $newSample->oldColumn_datedisposal=$var['oldColumn_datedisposal'];
                $newSample->oldColumn_mannerofdisposal=$var['oldColumn_mannerofdisposal'];
                $newSample->oldColumn_batch_num=$var['oldColumn_batch_num'];
                $newSample->oldColumn_package_count=$var['oldColumn_package_count'];
                $newSample->testcategory_id=$var['testcategory_id'];
                $newSample->old_request_id=$var['old_request_id'];
                if($newSample->save(true)){
                    // ctr not anymore here
                }else{
                    $ids=$ids.$var['sample_old_id'].',';
                }
                $ctr++;
            }
        }
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
         // echo $ctr;
         return [
            'num'=>$ctr,
            'ids'=>$ids
         ];   
    }
    public function actionSync_analysis(){ 
        $post = Yii::$app->request->post();
        $ctr = 0;
        if(isset($post)){
            $myvar = Json::decode($post['data']);
            $ids="";
            foreach ($myvar as $var) {
                $newanalysis = new AnalysisMigration();
                $newanalysis->rstl_id=$var['rstl_id'];
                $newanalysis->pstcanalysis_id=$var['pstcanalysis_id'];
                $newanalysis->sample_id=$var['sample_id'];
                $newanalysis->sample_code=$var['sample_code'];
                $newanalysis->testname=$var['testname'];
                $newanalysis->method=$var['method'];
                $newanalysis->references=$var['references'];
                $newanalysis->quantity=$var['quantity'];
                $newanalysis->fee=$var['fee'];
                $newanalysis->test_id=$var['test_id'];
                $newanalysis->cancelled=$var['cancelled'];
                $newanalysis->date_analysis=$var['date_analysis'];
                $newanalysis->user_id=$var['user_id'];
                $newanalysis->is_package=$var['is_package'];
                $newanalysis->oldColumn_deleted=$var['oldColumn_deleted'];
                $newanalysis->analysis_old_id=$var['analysis_old_id'];
                $newanalysis->oldColumn_taggingId=$var['oldColumn_taggingId'];
                $newanalysis->oldColumn_result=$var['oldColumn_result'];
                $newanalysis->oldColumn_package_count=$var['oldColumn_package_count'];
                $newanalysis->oldColumn_requestId=$var['oldColumn_requestId'];
                $newanalysis->request_id=$var['request_id'];
                $newanalysis->testcategory_id=$var['testcategory_id'];
                $newanalysis->sample_type_id=$var['sample_type_id'];
                $newanalysis->old_sample_id=$var['old_sample_id'];
                if($newanalysis->save(true)){
                    // ctr not anymore here
                }else{
                    $ids=$ids.$var['analysis_old_id'].',';
                }
                $ctr++;
            }
        }
         \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
         // echo $ctr;
         return [
            'num'=>$ctr,
            'ids'=>$ids
         ];   
    }
    /* ***************************************************************************8
      API for finance migration
    *
    */
      public function actionSync_check(){
        $post = Yii::$app->request->post(); //get the post
        $ctr = 0;
        if(isset($post)){
            $myvar = Json::decode($post['data']);
            $ids="";
            foreach ($myvar as $var) {
                $data = new CheckMigration();
                $data->receipt_id=$var['receipt_id'];
                $data->bank=$var['bank'];
                $data->checknumber=$var['checknumber'];
                $data->checkdate=$var['checkdate'];
                $data->amount=$var['amount'];
                $data->local_check_id=$var['local_check_id'];
                $data->local_receipt_id = $var['receipt_id'];
                if($data->save(true)){
                    //addtional action here if necessarry
                }else{
                    $ids=$ids.$var['local_check_id'].',';
                }
                $ctr++;
            }
           
        }
         \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
         return [
            'num'=>$ctr,
            'ids'=>$ids
         ];   
      }
      public function actionSync_receipt(){
        $post = Yii::$app->request->post(); //get the post
        $ctr = 0;
        if(isset($post)){
            $myvar = Json::decode($post['data']);
            $ids="";
            foreach ($myvar as $var) {
                $data = new ReceiptMigration();
                $data->rstl_id=$var['rstl_id'];
                $data->payment_mode_id=$var['payment_mode_id'];
                $data->payor=$var['payor'];
                $data->total=$var['total'];
                $data->or_series_id=$var['or_series_id'];
                $data->collectiontype_id=$var['collectiontype_id'];
                $data->receiptDate=$var['receiptDate'];
                $data->deposit_type_id=$var['deposit_type_id'];
                $data->or_number=$var['or_number'];
                $data->cancelled=$var['cancelled'];
                $data->oldColumn_check_money_number=$var['oldColumn_check_money_number'];
                $data->oldColumn_bank=$var['oldColumn_bank'];
                $data->oldColumn_checkdate=$var['oldColumn_checkdate'];
                $data->local_orderofpayment_id=$var['local_orderofpayment_id'];
                $data->local_receipt_id=$var['local_receipt_id'];
                $data->receipt_id=$var['receipt_id'];
                $data->terminal_id=$var['terminal_id'];
                $data->collection_id=$var['collection_id'];
                $data->deposit_id=$var['deposit_id'];
                $data->local_deposit_id=$var['local_deposit_id'];
                $data->local_deposit_type_id=$var['local_deposit_type_id'];
                $data->local_collection_id=$var['local_collection_id'];
                $data->orderofpayment_id=0;
                $data->customer_id=0; //double check this
                if($data->save(true)){
                    //addtional action here if necessarry
                }else{
                    $ids=$ids.$var['local_receipt_id'].',';
                }
                $ctr++;
            }
           
        }
         \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
         return [
            'num'=>$ctr,
            'ids'=>$ids
         ];   
      }
      public function actionSync_paymentitem(){
        $post = Yii::$app->request->post(); //get the post
        $ctr = 0;
        if(isset($post)){
            $myvar = Json::decode($post['data']);
            $ids="";
            foreach ($myvar as $var) {
                $data = new PaymentitemMigration();
                $data->rstl_id=$var['rstl_id'];
                $data->request_id=$var['request_id'];
                $data->orderofpayment_id=$var['orderofpayment_id'];
                $data->details=$var['details'];
                $data->amount=$var['amount'];
                $data->cancelled=$var['cancelled'];
                $data->local_paymentitem_id=$var['local_paymentitem_id'];
                $data->oldColumn_referral_id=$var['oldColumn_referral_id'];
                $data->request_type_id=1;
                $data->status=0;
                $data->receipt_id=0;
                $data->local_receipt_id=0;
                $data->local_orderofpayment_id=$var['orderofpayment_id'];
                if($data->save(true)){
                    //addtional action here if necessarry
                }else{
                    $ids=$ids.$var['local_paymentitem_id'].',';
                }
                $ctr++;
            }
           
        }
         \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
         return [
            'num'=>$ctr,
            'ids'=>$ids
         ];   
      }
      public function actionSync_orderofpayment(){
        $post = Yii::$app->request->post(); //get the post
        $ctr = 0;
        if(isset($post)){
          $myvar = Json::decode($post['data']);
          $ids="";
          foreach ($myvar as $var) {
            $data = new OrderofpaymentMigration();
            $data->rstl_id=$var['rstl_id'];
            $data->transactionnum=$var['transactionnum'];
            $data->collectiontype_id=$var['collectiontype_id'];
            $data->order_date=$var['order_date'];
            $data->customer_id=$var['customer_id'];
            $data->purpose=$var['purpose'];
            $data->total_amount=$var['total_amount'];
            $data->local_orderofpayment_id=$var['local_orderofpayment_id'];
            $data->oldColumn_customerName=$var['oldColumn_customerName'];
            $data->oldColumn_address=$var['oldColumn_address'];
            $data->payment_mode_id=$var['payment_mode_id'];
            $data->on_account=0;
            $data->receipt_id="";
            $data->payment_status_id=1;
            $data->subsidiary_customer_ids="";
            $data->local_customer_id=$var['customer_id'];
            if($data->save(true)){  
                    //addtional action here if necessarry 
            }else{  
                $ids=$ids.$var['local_orderofpayment_id'].',';  
            }
             $ctr++;
          }
        }
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON; 
          return [  
            'num'=>$ctr,  
            'ids'=>$ids 
         ]; 
      }
      public function actionSync_deposit(){ 
        $post = Yii::$app->request->post(); //get the post  
        $ctr = 0; 
        if(isset($post)){ 
            $myvar = Json::decode($post['data']); 
            $ids="";  
            foreach ($myvar as $var) {  
                 $data = new DepositMigration();  
                $data->rstl_id=$var['rstl_id']; 
                $data->or_series_id=$var['or_series_id']; 
                $data->start_or=$var['start_or']; 
                $data->end_or=$var['end_or']; 
                $data->deposit_date=$var['deposit_date']; 
                $data->amount=$var['amount']; 
                $data->deposit_type_id=$var['deposit_type_id']; 
                $data->local_deposit_id=$var['local_deposit_id']; 
                  
                if($data->save(true)){  
                    //addtional action here if necessarry 
                }else{  
                    $ids=$ids.$var['local_deposit_id'].','; 
                } 
                $ctr++;
            }
        }
        \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        return [
          'num'=>$ctr,
          'ids'=>$ids
        ];
      }
      public function actionSync_collection(){  
        $post = Yii::$app->request->post(); //get the post  
        $ctr = 0; 
        if(isset($post)){ 
            $myvar = Json::decode($post['data']); 
            $ids="";  
            foreach ($myvar as $var) {  
                 $data = new CollectionMigration(); 
                $data->rstl_id=$var['rstl_id']; 
                $data->referral_id=$var['referral_id']; 
                $data->oldColumn_receipt_id=$var['oldColumn_receipt_id']; 
                $data->nature=$var['nature']; 
                $data->amount=$var['amount']; 
                $data->oldColumn_receiptid=$var['oldColumn_receiptid']; 
                $data->oldColumn_cancelled=$var['oldColumn_cancelled']; 
                $data->oldColumn_request_id=$var['oldColumn_request_id']; 
                $data->collection_old_id=$var['collection_old_id']; 
                $data->wallet_amount=$var['wallet_amount']; 
                $data->sub_total=$var['sub_total']; 
                $data->payment_status_id=$var['payment_status_id']; 
                $data->local_orderofpayment_id=$var['local_orderofpayment_id'];
                if($data->save(true)){
                  //addtional action here if necessarry
                }else{          
                  $ids=$ids.$var['collection_old_id'].',';
                }
                 $ctr++;
           }
         }
          \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON; 
          return [  
            'num'=>$ctr,  
            'ids'=>$ids 
         ];   
       }
}
