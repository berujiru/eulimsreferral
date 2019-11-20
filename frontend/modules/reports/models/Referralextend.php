<?php

namespace frontend\modules\reports\models;

use Yii;
//use common\models\system\Rstl;
//use common\components\Functions;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\referral\Referral;
/**
 * Extended model Referral
 *
**/

class Referralextend extends Referral
{
    //public $from_date, $to_date, $lab_id;

    /*public function countSummary($labId,$requestDate,$startDate,$endDate,$summaryType,$requestType)
    {
        $function = new Functions();
        $connection = Yii::$app->labdb;
        $rstlId = Yii::$app->user->identity->profile->rstl_id;

        $query = $function->ExecuteStoredProcedureOne("spSummaryforSamples(:rstlId,:labId,:requestDate,:startDate,:endDate,:summaryType,:requestType)", 
            [':rstlId'=>$rstlId,':labId'=>$labId,':requestDate'=>$requestDate,':startDate'=>$startDate,':endDate'=>$endDate,':summaryType'=>$summaryType,':requestType'=>$requestType], $connection);
        return $query['Counter'];
    } */

    public function computeAccomplishment($labId,$referralDate,$startDate,$endDate,$generateType,$reportType)
    {
        if($reportType == 1){
            /*$connection = Yii::$app->db;
            $agencyId = Yii::$app->user->identity->profile->rstl_id;
            $params = [':agencyId' =>$agencyId, ':status' => 1];
            $query = $connection->createCommand('CALL `spAccomplishmentReport`(:rstlId,:labId,:requestDate,:startDate,:endDate,:generateType,:requestType)')
                ->bindValues($params)
                ->queryAll();*/
            $connection = Yii::$app->db;
            $agencyId = (int) Yii::$app->user->identity->profile->rstl_id;
            $params = [':agencyId'=>$agencyId,':referralDate'=>trim($referralDate),':startDate'=>trim($startDate),':endDate'=>trim($endDate),':generateType'=>(int) $generateType];
            $query = $connection->createCommand('CALL `spAccomplishmentReportSummaryAllAgency`(:agencyId,:referralDate,:startDate,:endDate,:generateType)')
                ->bindValues($params)
                ->queryAll();

            return $query[0]['ReturnValue'];

            //print_r($query);
            //exit;

        } else {
            //$function = new Functions();
            /*$connection = Yii::$app->db;
            $rstlId = Yii::$app->user->identity->profile->rstl_id;

            $query = $connection->createCommand('spAccomplishmentReport(:rstlId,:labId,:requestDate,:startDate,:endDate,:generateType,:requestType)')
            ->queryAll();

            //$query = $function->ExecuteStoredProcedureOne("spAccomplishmentReport(:rstlId,:labId,:requestDate,:startDate,:endDate,:generateType,:requestType)", 
                [':rstlId'=>$rstlId,':labId'=>$labId,':requestDate'=>$requestDate,':startDate'=>$startDate,':endDate'=>$endDate,':generateType'=>$generateType,':requestType'=>$requestType], $connection);
            return $query['Counter'];*/

            $connection = Yii::$app->db;
            $agencyId = (int) Yii::$app->user->identity->profile->rstl_id;
            $params = [':agencyId'=>$agencyId, ':labId'=>(int) $labId,':referralDate'=>trim($referralDate),':startDate'=>trim($startDate),':endDate'=>trim($endDate),':generateType'=>(int) $generateType];
            $query = $connection->createCommand('CALL `spAccomplishmentReportPerLaboratoryAgency`(:agencyId,:labId,:referralDate,:startDate,:endDate,:generateType)')
                ->bindValues($params)
                ->queryAll();

            //print_r($query);

            return $query[0]['ReturnValue'];
        }
    }
}
