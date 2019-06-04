<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_bid_notification".
 *
 * @property int $bid_notification_id
 * @property int $referral_id
 * @property int $postedby_agency_id
 * @property string $posted_at
 * @property int $recipient_agency_id
 * @property int $seen
 * @property string $seen_date
 */
class Bidnotification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_bid_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referral_id', 'postedby_agency_id', 'posted_at', 'recipient_agency_id'], 'required'],
            [['referral_id', 'postedby_agency_id', 'recipient_agency_id', 'seen'], 'integer'],
            [['posted_at', 'seen_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bid_notification_id' => 'Bid Notification ID',
            'referral_id' => 'Referral ID',
            'postedby_agency_id' => 'Postedby Agency ID',
            'posted_at' => 'Posted At',
            'recipient_agency_id' => 'Recipient Agency ID',
            'seen' => 'Seen',
            'seen_date' => 'Seen Date',
        ];
    }
}
