<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "barangay_for_old".
 *
 * @property int $id
 * @property int $municipalityCityId
 * @property int $district
 * @property string $name
 */
class Barangayforold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barangay_for_old';
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
            [['municipalityCityId', 'name'], 'required'],
            [['municipalityCityId', 'district'], 'integer'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'municipalityCityId' => 'Municipality City ID',
            'district' => 'District',
            'name' => 'Name',
        ];
    }
}
