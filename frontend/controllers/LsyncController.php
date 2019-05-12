<?php
namespace frontend\controllers;

use common\models\system\Logsync;
use yii\data\ActiveDataProvider;
use linslin\yii2\curl;

class LsyncController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$curl = new curl\Curl();
    	$request_token = "ef3efa12025da3e86c267e2f2ee52ff3574e5225";
    	$api_url_get = "https://api3.onelab.ph/access/get-access-token?tk=".$request_token."&id=11";
    	$query = Logsync::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //get http://example.com/
		$response = $curl->get($api_url_get);

		// var_dump($response); exit;


if ($curl->errorCode === null) {
   var_dump($response); exit;
} else {
	echo $curl->errorCode; exit;
     // List of curl error codes here https://curl.haxx.se/libcurl/c/libcurl-errors.html
    // switch ($curl->errorCode) {
    
    //     case 6:
    //         //host unknown example
    //         break;
    // }
} 




        return $this->render('index',[
        	'dataProvider'=>$dataProvider,
        	'$response'=>$$response       	
        	]);
    }

}
