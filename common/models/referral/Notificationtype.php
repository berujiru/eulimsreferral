<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_notification_type".
 *
 * @property int $notification_type_id
 * @property string $notification_type
 */
class Notificationtype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_notification_type';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('referraldb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notification_type'], 'required'],
            [['notification_type'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'notification_type_id' => 'Notification Type ID',
            'notification_type' => 'Notification Type',
        ];
    }
}
