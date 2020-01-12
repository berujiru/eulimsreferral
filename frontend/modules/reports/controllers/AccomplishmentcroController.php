<?php

namespace frontend\modules\reports\controllers;

use Yii;
use yii\web\Controller;
//use common\models\lab\Sample;
//use common\models\lab\SampleSearch;
//use common\models\lab\Request;
use frontend\modules\reports\models\Referralextend;
use common\models\referral\Lab;
use common\models\referral\Referral;
use common\models\referral\Agency;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
//use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use frontend\modules\referrals\template\Accomplishmentreportagency;

class AccomplishmentcroController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$model = new Referralextend;
    	$rstlId = Yii::$app->user->identity->profile->rstl_id;
    	$agency = Agency::findOne($rstlId);
    	
		if (Yii::$app->request->get())
		{
			$labId = (int) Yii::$app->request->get('lab_id');

			$report_type = (int) Yii::$app->request->get('report_type');
			
			if($this->checkValidDate(Yii::$app->request->get('from_date')) == true)
			{
		        $fromDate = Yii::$app->request->get('from_date');
			} else {
				$fromDate = date('Y-m-d');
				Yii::$app->session->setFlash('error', "Not a valid date!");
			}

			if($this->checkValidDate(Yii::$app->request->get('to_date')) == true){
				$toDate = Yii::$app->request->get('to_date');
			} else {
				$toDate = date('Y-m-d');
				Yii::$app->session->setFlash('error', "Not a valid date!");
			}

		} else {
			$labId = 1;
			$fromDate = date('Y-01-01'); //first day of the year
			$toDate = date('Y-m-d'); //as of today
			$report_type = 2;
		}

		if($report_type == 1){
			$modelReferral = Referralextend::find()
				->where('(testing_agency_id =:testingAgencyId OR receiving_agency_id =:receivingAgencyId) AND cancelled =:cancel AND DATE_FORMAT(`referral_date_time`, "%Y-%m-%d") BETWEEN :fromRequestDate AND :toRequestDate AND testing_agency_id != 0', [':testingAgencyId'=>$rstlId,':receivingAgencyId'=>$rstlId,':cancel'=>0,':fromRequestDate'=>$fromDate,':toRequestDate'=>$toDate])
				->groupBy(['DATE_FORMAT(referral_date_time, "%Y-%m")'])
				//->orderBy('referral_date_time DESC');
				//->groupBy(['DATE_FORMAT(referral_date_time, "%m")'])
				//->addGroupBy(['DATE_FORMAT(referral_date_time, "%Y")'])
				//->orderBy("DATE_FORMAT(`referral_date_time`, '%m') ASC, DATE_FORMAT(`referral_date_time`, '%Y') DESC");
				->orderBy([
					'DATE_FORMAT(referral_date_time, "%Y")' => SORT_DESC,
				    'DATE_FORMAT(referral_date_time, "%m")' => SORT_ASC,
				]);
		} else {
			$modelReferral = Referralextend::find()
				->where('(testing_agency_id =:testingAgencyId OR receiving_agency_id =:receivingAgencyId) AND cancelled =:cancel AND lab_id = :labId AND DATE_FORMAT(`referral_date_time`, "%Y-%m-%d") BETWEEN :fromRequestDate AND :toRequestDate AND testing_agency_id != 0', [':testingAgencyId'=>$rstlId,':receivingAgencyId'=>$rstlId,':cancel'=>0,':labId'=>$labId,':fromRequestDate'=>$fromDate,':toRequestDate'=>$toDate])
				->groupBy(['DATE_FORMAT(referral_date_time, "%Y-%m")'])
				//->orderBy('referral_date_time DESC');
				//->groupBy(['DATE_FORMAT(referral_date_time, "%m")'])
				//->addGroupBy(['DATE_FORMAT(referral_date_time, "%Y")'])
				//->orderBy("DATE_FORMAT(`referral_date_time`, '%m') ASC, DATE_FORMAT(`referral_date_time`, '%Y') DESC");
				->orderBy([
					'DATE_FORMAT(referral_date_time, "%Y")' => SORT_DESC,
				    'DATE_FORMAT(referral_date_time, "%m")' => SORT_ASC,
				]);
		}

		$dataProvider = new ActiveDataProvider([
            'query' => $modelReferral,
            'pagination' => false,
            // 'pagination' => [
            //     'pagesize' => 10,
            // ],
        ]);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'dataProvider' => $dataProvider,
                'lab_id' => $labId,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'model'=>$modelReferral,
	            'laboratories' => $this->listLaboratory(),
	            'reportType' => $report_type,
	            'agency' => $agency,
            ]);
        } else {
			return $this->render('index', [
	            'dataProvider' => $dataProvider,
	            'lab_id' => $labId,
	            'model'=>$modelReferral,
                'from_date' => $fromDate,
                'to_date' => $toDate,
	            'laboratories' => $this->listLaboratory(),
	            'reportType' => $report_type,
	            'agency' => $agency,
	        ]);
		}

        //return $this->render('index');
    }

    protected function listLaboratory()
    {
        $laboratory = ArrayHelper::map(Lab::find()->all(), 'lab_id', 
            function($laboratory, $defaultValue) {
                return $laboratory->labname;
        });

        return $laboratory;
    }

    function checkValidDate($date){
		$tempdate = explode('-', $date);

		if(count($tempdate) < 3 || count($tempdate) > 3)
		{
			return false;
		} else {
			$month = (int) $tempdate[1];
			$year = (int) $tempdate[0];
			$day = (int) $tempdate[2];
			// checkdate(month, day, year)
			if(checkdate($month,$day,$year) == true){
				return true;
			} else {
				return false;
			}
		}
	}
}
