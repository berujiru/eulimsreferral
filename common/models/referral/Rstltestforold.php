<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "rstl_test_for_old".
 *
 * @property int $id
 * @property int $localulims_id
 * @property int $rstl_id
 * @property string $testName
 * @property string $method
 * @property string $references
 * @property double $fee
 * @property int $duration
 * @property int $categoryId
 * @property int $sampleType
 * @property int $labId
 */
class Rstltestforold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rstl_test_for_old';
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
            [['localulims_id', 'rstl_id', 'testName', 'method', 'references', 'fee', 'duration', 'categoryId', 'sampleType', 'labId'], 'required'],
            [['localulims_id', 'rstl_id', 'duration', 'categoryId', 'sampleType', 'labId'], 'integer'],
            [['fee'], 'number'],
            [['testName'], 'string', 'max' => 200],
            [['method'], 'string', 'max' => 150],
            [['references'], 'string', 'max' => 100],
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
            'testName' => 'Test Name',
            'method' => 'Method',
            'references' => 'References',
            'fee' => 'Fee',
            'duration' => 'Duration',
            'categoryId' => 'Category ID',
            'sampleType' => 'Sample Type',
            'labId' => 'Lab ID',
        ];
    }
}
