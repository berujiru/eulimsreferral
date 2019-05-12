<?php

namespace common\models\api;

use Yii;

/**
 * This is the model class for table "tbl_order_of_payment".
 *
 * @property int $op_id
 * @property string $transaction_num
 * @property string $customer_code
 * @property string $collection_code
 * @property string $collection_type
 * @property string $order_date
 * @property string $agency_code
 * @property string $total_amount
 * @property string $op_status
 */
class OrderOfPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_order_of_payment';
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
            [['transaction_num', 'customer_code', 'collection_code', 'collection_type', 'order_date', 'agency_code', 'total_amount', 'op_status'], 'required'],
            [['transaction_num', 'customer_code', 'collection_code', 'collection_type', 'order_date', 'agency_code', 'total_amount', 'op_status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'op_id' => 'Op ID',
            'transaction_num' => 'Transaction Num',
            'customer_code' => 'Customer Code',
            'collection_code' => 'Collection Code',
            'collection_type' => 'Collection Type',
            'order_date' => 'Order Date',
            'agency_code' => 'Agency Code',
            'total_amount' => 'Total Amount',
            'op_status' => 'Op Status',
        ];
    }
}
