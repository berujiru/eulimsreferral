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
 * @property string $discount_rate
 * @property string $submitted_by
 * @property string $received_by
 * @property int $user_id
 * @property int $status_id
 * @property int $accepted
 * @property int $local_request_id
 * @property string $sample_received_date
 * @property int $is_referral
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
            [['rstl_id', 'pstc_id', 'customer_id', 'submitted_by', 'received_by', 'user_id', 'status_id', 'created_at'], 'required'],
            [['rstl_id', 'pstc_id', 'customer_id', 'discount_id', 'user_id', 'status_id', 'accepted', 'local_request_id', 'is_referral'], 'integer'],
            [['discount_rate'], 'number'],
            [['sample_received_date', 'created_at', 'updated_at'], 'safe'],
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
            'rstl_id' => 'Rstl ID',
            'pstc_id' => 'Pstc ID',
            'customer_id' => 'Customer ID',
            'discount_id' => 'Discount ID',
            'discount_rate' => 'Discount Rate',
            'submitted_by' => 'Submitted By',
            'received_by' => 'Received By',
            'user_id' => 'User ID',
            'status_id' => 'Status ID',
            'accepted' => 'Accepted',
            'local_request_id' => 'Local Request ID',
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
