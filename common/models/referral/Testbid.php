<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_test_bid".
 *
 * @property int $test_bid_id
 * @property int $bidder_agency_id
 * @property int $referral_id
 * @property int $bid_id
 * @property int $analysis_id
 * @property string $fee
 */
class Testbid extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_test_bid';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bidder_agency_id', 'referral_id', 'bid_id', 'analysis_id', 'fee'], 'required'],
            [['bidder_agency_id', 'referral_id', 'bid_id', 'analysis_id'], 'integer'],
            [['fee'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'test_bid_id' => 'Test Bid ID',
            'bidder_agency_id' => 'Bidder Agency ID',
            'referral_id' => 'Referral ID',
            'bid_id' => 'Bid ID',
            'analysis_id' => 'Analysis ID',
            'fee' => 'Fee',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalysis()
    {
        return $this->hasOne(Analysis::className(), ['analysis_id' => 'analysis_id']);
    }
}
