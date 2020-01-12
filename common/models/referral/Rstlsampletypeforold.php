<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "rstl_sampletype_for_old".
 *
 * @property int $id
 * @property int $rstl_id
 * @property int $localulims_id
 * @property string $sampleType
 * @property int $testCategoryId
 */
class Rstlsampletypeforold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rstl_sampletype_for_old';
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
            [['rstl_id', 'localulims_id', 'sampleType', 'testCategoryId'], 'required'],
            [['rstl_id', 'localulims_id', 'testCategoryId'], 'integer'],
            [['sampleType'], 'string', 'max' => 75],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rstl_id' => 'Rstl ID',
            'localulims_id' => 'Localulims ID',
            'sampleType' => 'Sample Type',
            'testCategoryId' => 'Test Category ID',
        ];
    }
}
