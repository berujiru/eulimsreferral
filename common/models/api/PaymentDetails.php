<?php

namespace common\models\api;

use Yii;

/**
 * This is the model class for table "tbl_payment_details".
 *
 * @property int $rrn_id
 * @property int $op_id
 * @property string $request_ref_num
 * @property string $rrn_date_time
 * @property string $amount
 */
class PaymentDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_payment_details';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('financedb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['op_id', 'request_ref_num', 'rrn_date_time', 'amount'], 'required'],
            [['op_id'], 'integer'],
            [['request_ref_num', 'rrn_date_time', 'amount'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rrn_id' => 'Rrn ID',
            'op_id' => 'Op ID',
            'request_ref_num' => 'Request Ref Num',
            'rrn_date_time' => 'Rrn Date Time',
            'amount' => 'Amount',
        ];
    }
}
