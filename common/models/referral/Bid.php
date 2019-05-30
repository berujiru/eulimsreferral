<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_bid".
 *
 * @property int $bid_id
 * @property int $referral_id
 * @property int $bidder_agency_id
 * @property string $sample_requirements
 * @property string $bid_amount
 * @property string $remarks
 * @property string $estimated_due
 * @property int $seen
 * @property string $seen_date
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Referral $referral
 */
class Bid extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_bid';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referral_id', 'bidder_agency_id', 'sample_requirements', 'bid_amount', 'remarks', 'estimated_due', 'seen', 'created_at', 'updated_at'], 'required'],
            [['referral_id', 'bidder_agency_id', 'seen'], 'integer'],
            [['sample_requirements'], 'string'],
            [['bid_amount'], 'number'],
            [['estimated_due', 'seen_date', 'created_at', 'updated_at'], 'safe'],
            [['remarks'], 'string', 'max' => 200],
            [['referral_id'], 'exist', 'skipOnError' => true, 'targetClass' => Referral::className(), 'targetAttribute' => ['referral_id' => 'referral_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bid_id' => 'Bid ID',
            'referral_id' => 'Referral ID',
            'bidder_agency_id' => 'Bidder Agency ID',
            'sample_requirements' => 'Sample Requirements',
            'bid_amount' => 'Bid Amount',
            'remarks' => 'Remarks',
            'estimated_due' => 'Estimated Due',
            'seen' => 'Seen',
            'seen_date' => 'Seen Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferral()
    {
        return $this->hasOne(Referral::className(), ['referral_id' => 'referral_id']);
    }
}
