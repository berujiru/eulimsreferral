<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_booking".
 *
 * @property int $booking_id
 * @property string $scheduled_date
 * @property string $booking_reference
 * @property string $description
 * @property int $rstl_id
 * @property string $date_created
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_booking';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('labdb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scheduled_date', 'date_created'], 'safe'],
            [['rstl_id'], 'integer'],
            [['booking_reference'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'booking_id' => 'Booking ID',
            'scheduled_date' => 'Scheduled Date',
            'booking_reference' => 'Booking Reference',
            'description' => 'Description',
            'rstl_id' => 'Rstl ID',
            'date_created' => 'Date Created',
        ];
    }
}
