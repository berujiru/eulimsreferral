<?php
use linslin\yii2\curl;
use yii\helpers\Json;

/* 
 * Project Name: eulims_ * 
 * Copyright(C)2018 Department of Science & Technology -IX * 
 * Developer: Eden Galleno  * 
 * 09 10, 18 , 11:03:50 AM * 
 * Module: backup_finance * 
 */

//$apiUrl="https://api3.onelab.ph/lab/get-lab?id=11";
//$response = $curl->get($apiUrl);
////$decode= Json::decode($response);
//
//    var_dump($response);
$curl = new curl\Curl();
    
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
                   [
                     'table_name'=>'tbl_sample',
                     'start_id'=>'1',
                     'last_id'=>10
                   ]
                ]
            ],
           
            
        ];
       // $content = json_encode($sync);
        Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
        return $sync;
?>
<div class="system-default-index">
    
</div>

