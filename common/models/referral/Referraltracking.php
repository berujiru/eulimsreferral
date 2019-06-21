<?php

/*
 * Project Name: eulims_ * 
 * Copyright(C)2019 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 06 11, 19 , 1:56:35 PM * 
 * Module: Referraltracking * 
 */

namespace common\models\referral;
use yii\base\Model;
use common\models\referral\Referraltrackreceiving;
use common\models\referral\Referraltracktesting;
use common\models\referral\Attachment;
/**
 * Description of Referraltracking
 *
 * @author ts-ict5
 */
class Referraltracking extends Referral{
    
    //public $referral_code;
    public $from_date, $to_date;
    //put your code here
     public function rules()
    {
        return [
           // [['referral_code'], 'required'],
            [['from_date', 'to_date'], 'safe'],
            [['referral_date_time', 'sample_received_date', 'report_due', 'created_at_local', 'create_time', 'update_time'], 'safe'],
            [['local_request_id', 'receiving_agency_id', 'testing_agency_id', 'lab_id', 'customer_id', 'payment_type_id', 'modeofrelease_id', 'purpose_id', 'discount_id', 'receiving_user_id', 'testing_user_id', 'bid', 'cancelled'], 'integer'],
            [['discount_rate', 'total_fee'], 'number'],
            [['referral_code'], 'string', 'max' => 50],
            [['conforme', 'cro_receiving', 'cro_testing'], 'string', 'max' => 100],
            [['payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paymenttype::className(), 'targetAttribute' => ['payment_type_id' => 'payment_type_id']],
            [['modeofrelease_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modeofrelease::className(), 'targetAttribute' => ['modeofrelease_id' => 'modeofrelease_id']],
            [['lab_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lab::className(), 'targetAttribute' => ['lab_id' => 'lab_id']],
            [['discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discount::className(), 'targetAttribute' => ['discount_id' => 'discount_id']],
            [['purpose_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purpose::className(), 'targetAttribute' => ['purpose_id' => 'purpose_id']],
           
        ];
    }
     public function attributeLabels()
    {
        return [
            'referral_id' => 'Referral ID',
            'referral_code' => 'Referral Code',
            'referral_date_time' => 'Referral Date Time',
            'local_request_id' => 'Local Request ID',
            'receiving_agency_id' => 'Receiving Laboratory',
            'testing_agency_id' => 'Testing/ Calibration Laboratory',
            'lab_id' => 'Lab ID',
            'sample_received_date' => 'Date Sample Received (Customer)',
            'customer_id' => 'Customer ID',
            'payment_type_id' => 'Payment Type ID',
            'modeofrelease_id' => 'Modeofrelease ID',
            'purpose_id' => 'Purpose ID',
            'discount_id' => 'Discount ID',
            'discount_rate' => 'Discount Rate',
            'total_fee' => 'Total Fee',
            'report_due' => 'Report Due',
            'conforme' => 'Conforme',
            'receiving_user_id' => 'Receiving User ID',
            'cro_receiving' => 'Cro Receiving',
            'testing_user_id' => 'Testing User ID',
            'cro_testing' => 'Cro Testing',
            'bid' => 'Bid',
            'cancelled' => 'Cancelled',
            'created_at_local' => 'Created At Local',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
    public function getReferraltrackreceivings()
    {
        return $this->hasOne(Referraltrackreceiving::className(), ['referral_id' => 'referral_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferraltracktestings()
    {
        return $this->hasOne(Referraltracktesting::className(), ['referral_id' => 'referral_id']);
    }
    
    public function getReferralattachment()
    {
       // return $this->hasOne(Attachment::className(), ['referral_id' => 'referral_id']);
        $attachment = Attachment::find()->where(['referral_id' => $this->referral_id])
                 ->orderBy('upload_date','ASC')->one();
        return $attachment;
    }
    
    public function getComputeduration()
    {
        $attachment = $this->getReferralattachment();

        $uploadDate = $attachment->upload_date;

        if(date("Y-m-d",strtotime($this->referral_date_time)) == "" || $uploadDate == "" || $uploadDate <= 0){
                $number = "";
        } else {
                $endDate = date("Y-m-d",strtotime($uploadDate));
                $start = date_create(date("Y-m-d",strtotime($this->referral_date_time)));
                $end = date_create($endDate);	
                $cycle = date_diff($start,$end);
                $days = $cycle->format("%a");
                if($days == 1){
                        //$days .= ' days';
                        $number = $days." day";
                } elseif($days > 1) {
                        $number = $days." days";
                } else {
                        $number = "less than 1 day";
                }
        }

        return $number;
    }
    
     public static function getComputecycle($startDate,$endDate)
    {
        if($startDate == "" || $endDate == ""){
                $number = "";
        } else {
            $start = date_create($startDate);
            $end = date_create($endDate);	
            $cycle = date_diff($start,$end);
            $days = $cycle->format("%a");

            if($startDate > $endDate){
                    $minus = "-";
            } else {
                    $minus = "";
            }

            if($days == 1){
                    $number = "(".$days." day)";
            } elseif($days >= 1) {
                    $number = "(".$minus.$days." days)";
            } else {
                    $number = "(less than 1 day)";
            }
        }

        return $number;
    }
    
    
   
}
