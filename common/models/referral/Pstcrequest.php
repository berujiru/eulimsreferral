<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_pstcrequest".
 *
 * @property int $pstc_request_id
 * @property int $rstl_id
 * @property int $pstc_id
 * @property int $customer_id
 * @property int $discount_id
 * @property string $submitted_by
 * @property string $received_by
 * @property int $status_id
 * @property int $accepted
 * @property int $local_request_id
 * @property int $pstc_respond_id
 * @property string $created_at
 * @property string $updated_at
 */
class Pstcrequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_pstcrequest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rstl_id', 'pstc_id', 'customer_id', 'submitted_by', 'received_by', 'status_id', 'created_at'], 'required'],
            [['rstl_id', 'pstc_id', 'customer_id', 'discount_id', 'status_id', 'accepted', 'local_request_id','is_referral'], 'integer'],
            [['created_at', 'updated_at','sample_received_date'], 'safe'],
            [['submitted_by', 'received_by'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pstc_request_id' => 'Pstc Request ID',
            'rstl_id' => 'Rstl',
            'pstc_id' => 'Pstc',
            'customer_id' => 'Customer',
            'discount_id' => 'Discount',
            'submitted_by' => 'Submitted By',
            'received_by' => 'Received By',
            'status_id' => 'Status',
            'accepted' => 'Accepted',
            'local_request_id' => 'Local Request',
			'sample_received_date' => 'Sample Received Date',
			'is_referral' => 'Is Referral',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSamples()
    {
        return $this->hasMany(Pstcsample::className(), ['pstc_request_id' => 'pstc_request_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getRespond()
    {
        return $this->hasOne(Pstcrespond::className(), ['pstc_request_id' => 'pstc_request_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPstc()
    {
        return $this->hasOne(Pstc::className(), ['pstc_id' => 'pstc_id']);
    }

}
