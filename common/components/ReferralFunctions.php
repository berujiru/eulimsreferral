<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\JsExpression;
use yii\web\NotFoundHttpException;
use linslin\yii2\curl;
use common\models\referral\Referral;
use common\models\referral\Sample;
use common\models\referral\Analysis;
use common\models\referral\Notification;

/**
 * Referral User Defined Functions
 * @author OneLab
 */
class ReferralFunctions extends Component
{

	//check if the agency is notified
	function checkNotified($referralId,$recipientId)
	{
		if($referralId > 0 && $recipientId > 0){
			$check = Notification::find()
				->where('referral_id =:referralId', [':referralId'=>$referralId])
				->andWhere('recipient_id =:recipientId', [':recipientId'=>$recipientId])
				->andWhere('notification_type_id =:notice', [':notice'=>1])
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

	//check if the agency is the testing lab of the referral request
	function checkTestingLab($referralId,$recipientId)
	{
		if($referralId > 0 && $recipientId > 0){
			$check = Notification::find()
				->where('referral_id =:referralId', [':referralId'=>$referralId])
				->andWhere('recipient_id =:recipientId', [':recipientId'=>$recipientId])
				->andWhere('notification_type_id =:notice', [':notice'=>3])
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

	//check if the agency is the owner of the referral request
	function checkowner($referralId,$senderId)
	{
		if($referralId > 0 && $senderId > 0){
			$check = Referral::find()
				->where('referral_id =:referralId', [':referralId'=>$referralId])
				->andWhere('receiving_agency_id =:senderId', [':senderId'=>$senderId])
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

	public function actionViewdetail()
	{
		$getrequest = Yii::$app->request;
		
		if(!empty($getrequest->get('referral_id')) && !empty($getrequest->get('rstl_id'))){
			$referralId = (int) $getrequest->get('referral_id');
			$recipientId = (int) $getrequest->get('rstl_id');
			$senderId = (int) $getrequest->get('rstl_id');
			
			$checkNotified = $this->checkNotified($referralId,$recipientId);
			$checkOwner = $this->actionCheckowner($referralId,$senderId);
			
			if($checkNotified > 0 || $checkOwner > 0){
				$modelReferral = new $this->modelClass;
				$modelSample = new $this->modelSampleClass;
				$modelAnalysis = new $this->modelAnalysisClass;
				$modelCustomer = new $this->modelCustomerClass;
				
				$referral = $modelReferral::find()
					->where('referral_id =:referralId', [':referralId'=>$referralId])
					->one();
				
				$samples = $modelSample::find()
					->where('referral_id =:referralId', [':referralId'=>$referralId])
					->asArray()
					->all();
					
				$sampleIds = implode(',', array_map(function ($data) {
					return $data['sample_id'];
				}, $samples));
					
				$analyses = $modelAnalysis::find()
					->select('tbl_analysis.*,tbl_sample.sample_name,tbl_sample.sample_code,tbl_testname.test_name,tbl_methodreference.method,tbl_methodreference.reference')
					->joinWith(['sample','testname','methodreference'],false)
					->where(['in', 'tbl_analysis.sample_id', explode(',',$sampleIds)])
					//->andWhere(['cancelled'=>0])
					->asArray()
					->all();
					
				$customer = $modelCustomer::find()
					->where('customer_id =:customerId', [':customerId'=>$referral->customer_id])
					->one();
				
				$data = ['request_data'=>$referral,'sample_data'=>$samples,'analysis_data'=>$analyses,'customer_data'=>$customer];
				
				return $data;
				
			} else {
				//return "Your agency doesn't appear notified!";
				return 0;
			}
		} else {
			throw new \yii\web\HttpException(400, 'No records found');
		}
	}
}