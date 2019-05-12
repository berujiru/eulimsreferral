<?php

/*
 * Project Name: eulims_ * 
 * Copyright(C)2018 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 09 7, 18 , 4:38:40 PM * 
 * Module: BackupController * 
 */

namespace common\modules\system\controllers;

use Yii;
use yii\web\Controller;
use linslin\yii2\curl;
use yii\helpers\Json;
use common\models\finance\Op;
use common\models\finance\Paymentitem;
use common\models\finance\SubsidiaryCustomer;
use common\models\finance\Receipt;
use common\models\finance\Accountingcode;
use common\models\finance\Accountingcodemapping;
use common\models\finance\Billing;
use common\models\finance\Soa;
use common\models\finance\SoaBilling;
use common\models\finance\SoaReceipt;
use common\models\lab\Customer;
/**
 * Description of BackupController
 *
 * @author OneLab
 */
class UtilitiesController extends Controller {
    public function actionBackupRestore()
    {
        return $this->render('backup_restore');
    }
    public function actionBackupLab(){
        return "Lab";
    }
    public function actionBackupFinance(){
        $op=Op::find()->where(['between','orderofpayment_id',1,10])->all();
        $paymentitem=Paymentitem::find()->where(['between','orderofpayment_id',1,10])->all();
        $sc= SubsidiaryCustomer::find()->where(['between','orderofpayment_id',1,10])->all();
        $receipt= Receipt::find()->where(['between','orderofpayment_id',1,10])->all();
      //  $accounting_code= Accountingcode::find()->where(['between','orderofpayment_id',1,10])->all();
        $billing= Billing::find()->where(['between','billing_id',1,10])->all();
        $soa= Soa::find()->where(['between','soa_id',1,10])->all();
        $sync=[
            'sync_log'=>[
                'date_sync'=>'09/11/2018 10:01:12',
                'rstl_id'=>11,
                'local_user_id'=>1,
                'sync_details'=>[
                   [
                     'table_name'=>'tbl_request',
                     'start_id'=>'1',
                     'last_id'=>10
                   ],
                ]
            ],
            'op'=>$op,
            'paymentitem'=>$paymentitem,
            'subsidiary_customer'=>$sc,
            'receipt'=>$receipt,
            'billing'=>$billing,
            'soa'=>$soa,
            
        ];
       
//        Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
//        return $sync;
    
       // $content = json_encode($sync);
        $curl = new curl\Curl();
        $url="http://api3.onelab.ph.local/finance/sync-finance";
        
        $response = $curl->setRequestBody(json_encode($sync))
        /*->setHeaders([
           'Content-Type' => 'application/json',
           'Content-Length' => strlen(json_encode($sync))
        ])*/
        ->post($url);
        return $response;
    }
    public function actionBackupInventory(){
        return "Inventory";
    }
    public function actionRestoreCustomer(){
        $model = new Customer();
        $rstl_id=$model->rstl_id=Yii::$app->user->identity->profile->rstl_id;
        
        $apiUrl="https://api3.onelab.ph/get/customer/view?rstl_id=11";
        $curl = new curl\Curl();
        $res = $curl->get($apiUrl);
        
//          for($i=0;$i<$arr_length;$i++){
//            $request =$this->findRequest($str_request[$i]);
//            $paymentitem = new Paymentitem();
//            $paymentitem->rstl_id =Yii::$app->user->identity->profile ? Yii::$app->user->identity->profile->rstl_id : 11;
//            $paymentitem->request_id = $str_request[$i];
//            $paymentitem->orderofpayment_id = $opid;
//            $paymentitem->details =$request->request_ref_num;
//            $total=$request->total;
//            $amount=$request->getBalance($str_request[$i],$total);
//            $total_amount+=$amount;
//            $paymentitem->amount = $amount;
//            $paymentitem->request_type_id =$request->request_type_id;
//            $paymentitem->status=1;//Unpaid
//            $paymentitem->save(false); 
//
//        }
//        $post=[];
//            $posted=current($_POST['Paymentitem']);
//            $post['Paymentitem']=$posted;
//            if($paymentitem->load($post))
//            {
//                $paymentitem->save();
//            }
       // $customerlist= ArrayHelper::map( $decode,'lab_id','labname');
       
        $decode=Json::decode($res);
        $exist=0;
        $not_exist=0;
        foreach ($decode as $customer){
            $cust=Customer::find()->where(['customer_code'=>$customer['customer_code']])->all();
            if($cust){
               $exist+=1; 
            }
            else{
                $model_customer = new Customer();
                $model_customer->rstl_id=$rstl_id;
                $model_customer->customer_code=$customer['customer_code'];
                $model_customer->customer_name=$customer['customer_name'];
                $model_customer->classification_id=$customer['classification_id'];
                $model_customer->latitude=$customer['latitude'];
                $model_customer->longitude=$customer['longitude'];
                $model_customer->head=$customer['head'];
                $model_customer->barangay_id=$customer['barangay_id'];
                $model_customer->address=$customer['address'];
                $model_customer->tel=$customer['tel'];
                $model_customer->fax=$customer['fax'];
                $model_customer->email=$customer['email'];
                $model_customer->customer_type_id=$customer['customer_type_id'];
                $model_customer->business_nature_id=$customer['business_nature_id'];
                $model_customer->industrytype_id=$customer['industrytype_id'];
                $model_customer->created_at=$customer['created_at'];
                $model_customer->customer_old_id=$customer['customer_old_id'];
                $model_customer->Oldcolumn_municipalitycity_id=$customer['Oldcolumn_municipalitycity_id'];
                $model_customer->Oldcolumn_district=$customer['Oldcolumn_district'];
                $model_customer->save();
                $not_exist+=1;
            }
           
        }
         echo $exist;
            echo "<br>";
            echo $not_exist;  
            
     // return $res;
    }
}
