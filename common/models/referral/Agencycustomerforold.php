<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "agencycustomer_for_old".
 *
 * @property int $id
 * @property int $localulims_id
 * @property int $rstl_id
 * @property string $customerCode
 * @property string $customerName
 * @property string $head
 * @property int $municipalitycity_id
 * @property int $barangay_id
 * @property int $district
 * @property string $address
 * @property string $tel
 * @property string $fax
 * @property string $email
 * @property int $typeId
 * @property int $natureId
 * @property int $industryId
 * @property string $created
 */
class Agencycustomerforold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agencycustomer_for_old';
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
            [['localulims_id', 'rstl_id', 'customerName', 'head', 'municipalitycity_id', 'barangay_id', 'district', 'address', 'tel', 'fax', 'email', 'typeId', 'natureId', 'industryId'], 'required'],
            [['localulims_id', 'rstl_id', 'municipalitycity_id', 'barangay_id', 'district', 'typeId', 'natureId', 'industryId'], 'integer'],
            [['created'], 'safe'],
            [['customerCode'], 'string', 'max' => 11],
            [['customerName', 'address'], 'string', 'max' => 200],
            [['head'], 'string', 'max' => 100],
            [['tel', 'fax', 'email'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'localulims_id' => 'Localulims ID',
            'rstl_id' => 'Rstl ID',
            'customerCode' => 'Customer Code',
            'customerName' => 'Customer Name',
            'head' => 'Head',
            'municipalitycity_id' => 'Municipalitycity ID',
            'barangay_id' => 'Barangay ID',
            'district' => 'District',
            'address' => 'Address',
            'tel' => 'Tel',
            'fax' => 'Fax',
            'email' => 'Email',
            'typeId' => 'Type ID',
            'natureId' => 'Nature ID',
            'industryId' => 'Industry ID',
            'created' => 'Created',
        ];
    }
}
