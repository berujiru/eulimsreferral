<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\JsExpression;
use yii\web\NotFoundHttpException;
use linslin\yii2\curl;
use common\models\referral\Pstcrequest;
use common\models\referral\Pstcsample;
use common\models\referral\Pstcanalysis;
use common\models\referral\Agency;

/**
 * Referral User Defined Functions
 * @author OneLab
 */
class PstcFunctions extends Component
{
	//public $source = 'https://eulimsapi.onelab.ph';

	//check if the agency is the owner of the pstc request
	function checkOwner($requestId,$rstlId,$pstcId)
	{
		if($requestId > 0 && $rstlId > 0 && $pstcId > 0){
			$check = Pstcrequest::find()
				->where('pstc_request_id =:requestId', [':requestId' => $requestId])
				->andWhere('rstl_id =:rstlId', [':rstlId' => $rstlId])
				->andWhere('pstc_id =:pstcId', [':pstcId' => $pstcId])
				->count();
			
			if($check > 0){
				$status = 1;
			} else {
				$status = 0;
			}
			return $status;
		} else {
			return 0;
		}
	}
}