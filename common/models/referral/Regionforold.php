<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "region_for_old".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @property ProvinceForOld[] $provinceForOlds
 */
class Regionforold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region_for_old';
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
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvinceForOlds()
    {
        return $this->hasMany(ProvinceForOld::className(), ['regionId' => 'id']);
    }
}
