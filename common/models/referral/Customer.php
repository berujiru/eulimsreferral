<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_customer".
 *
 * @property int $customer_id
 * @property int $rstl_id
 * @property string $customer_code
 * @property string $customer_name
 * @property int $classification_id
 * @property double $latitude
 * @property double $longitude
 * @property string $head
 * @property int $barangay_id
 * @property string $address
 * @property string $tel
 * @property string $fax
 * @property string $email
 * @property int $customer_type_id
 * @property int $business_nature_id
 * @property int $industrytype_id
 * @property int $created_at
 * @property int $customer_old_id
 * @property int $Oldcolumn_municipalitycity_id
 * @property int $Oldcolumn_district
 * @property int $local_customer_id
 *
 * @property Customertype $customerType
 * @property Businessnature $businessNature
 * @property Industrytype $industrytype
 * @property Request[] $requests
 * @property Request[] $requests0
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_customer';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('eulimsdb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rstl_id', 'customer_name', 'head', 'barangay_id', 'address', 'tel', 'fax', 'email', 'customer_type_id', 'business_nature_id', 'industrytype_id', 'created_at'], 'required'],
            [['rstl_id', 'classification_id', 'barangay_id', 'customer_type_id', 'business_nature_id', 'industrytype_id', 'created_at', 'customer_old_id', 'Oldcolumn_municipalitycity_id', 'Oldcolumn_district', 'local_customer_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['customer_code'], 'string', 'max' => 11],
            [['customer_name', 'address'], 'string', 'max' => 200],
            [['head'], 'string', 'max' => 100],
            [['tel', 'fax', 'email'], 'string', 'max' => 50],
            [['customer_name', 'head', 'address'], 'unique', 'targetAttribute' => ['customer_name', 'head', 'address']],
            [['customer_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customertype::className(), 'targetAttribute' => ['customer_type_id' => 'customertype_id']],
            [['business_nature_id'], 'exist', 'skipOnError' => true, 'targetClass' => Businessnature::className(), 'targetAttribute' => ['business_nature_id' => 'business_nature_id']],
            [['industrytype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Industrytype::className(), 'targetAttribute' => ['industrytype_id' => 'industrytype_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'rstl_id' => 'Rstl ID',
            'customer_code' => 'Customer Code',
            'customer_name' => 'Customer Name',
            'classification_id' => 'Classification ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'head' => 'Head',
            'barangay_id' => 'Barangay ID',
            'address' => 'Address',
            'tel' => 'Tel',
            'fax' => 'Fax',
            'email' => 'Email',
            'customer_type_id' => 'Customer Type ID',
            'business_nature_id' => 'Business Nature ID',
            'industrytype_id' => 'Industrytype ID',
            'created_at' => 'Created At',
            'customer_old_id' => 'Customer Old ID',
            'Oldcolumn_municipalitycity_id' => 'Oldcolumn Municipalitycity ID',
            'Oldcolumn_district' => 'Oldcolumn District',
            'local_customer_id' => 'Local Customer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerType()
    {
        return $this->hasOne(Customertype::className(), ['customertype_id' => 'customer_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessNature()
    {
        return $this->hasOne(Businessnature::className(), ['business_nature_id' => 'business_nature_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndustrytype()
    {
        return $this->hasOne(Industrytype::className(), ['industrytype_id' => 'industrytype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests0()
    {
        return $this->hasMany(Request::className(), ['customer_id' => 'customer_id']);
    }
}
