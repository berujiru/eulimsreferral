<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_type_fee".
 *
 * @property int $type_fee_id
 * @property string $type_fee
 */
class Typefee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_type_fee';
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
            [['type_fee'], 'required'],
            [['type_fee'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'type_fee_id' => 'Type Fee ID',
            'type_fee' => 'Type Fee',
        ];
    }
}
