<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "province_for_old".
 *
 * @property int $id
 * @property int $regionId
 * @property string $name
 * @property string $code
 *
 * @property RegionForOld $region
 */
class Provinceforold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'province_for_old';
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
            [['regionId', 'name', 'code'], 'required'],
            [['regionId'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 10],
            [['regionId'], 'exist', 'skipOnError' => true, 'targetClass' => RegionForOld::className(), 'targetAttribute' => ['regionId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'regionId' => 'Region ID',
            'name' => 'Name',
            'code' => 'Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(RegionForOld::className(), ['id' => 'regionId']);
    }
}
