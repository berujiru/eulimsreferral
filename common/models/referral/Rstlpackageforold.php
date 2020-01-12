<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "rstl_package_for_old".
 *
 * @property int $id
 * @property int $localulims_id
 * @property int $rstl_id
 * @property int $testcategory_id
 * @property int $sampletype_id
 * @property string $name
 * @property double $rate
 * @property string $tests
 */
class Rstlpackageforold extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rstl_package_for_old';
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
            [['localulims_id', 'rstl_id', 'testcategory_id', 'sampletype_id'], 'integer'],
            [['rstl_id', 'testcategory_id', 'sampletype_id', 'name', 'rate', 'tests'], 'required'],
            [['rate'], 'number'],
            [['name'], 'string', 'max' => 40],
            [['tests'], 'string', 'max' => 100],
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
            'testcategory_id' => 'Testcategory ID',
            'sampletype_id' => 'Sampletype ID',
            'name' => 'Name',
            'rate' => 'Rate',
            'tests' => 'Tests',
        ];
    }
}
