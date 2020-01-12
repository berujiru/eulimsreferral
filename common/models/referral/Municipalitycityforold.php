<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "municipality_city_for_old".
 *
 * @property int $id
 * @property int $regionId
 * @property int $provinceId
 * @property string $name
 * @property int $district
 */
class Municipalitycityforold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'municipality_city_for_old';
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
            [['regionId', 'provinceId', 'name'], 'required'],
            [['regionId', 'provinceId', 'district'], 'integer'],
            [['name'], 'string', 'max' => 100],
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
            'provinceId' => 'Province ID',
            'name' => 'Name',
            'district' => 'District',
        ];
    }
}
