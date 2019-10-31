<?php

namespace common\models\referraladmin;

use Yii;

/**
 * This is the model class for table "tbl_testname".
 *
 * @property int $testname_id
 * @property string $test_name
 * @property int $active
 * @property string $create_time
 * @property string $update_time
 *
 * @property Analysis[] $analyses
 * @property Sampletypetestname[] $sampletypetestnames
 * @property TestnameMethod[] $testnameMethods
 */
class Testname extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_testname';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['test_name', 'create_time'], 'required'],
            [['active'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['test_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'testname_id' => 'Testname ID',
            'test_name' => 'Test Name',
            'active' => 'Active',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnalyses()
    {
        return $this->hasMany(Analysis::className(), ['testname_id' => 'testname_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampletypetestnames()
    {
        return $this->hasMany(Sampletypetestname::className(), ['testname_id' => 'testname_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestnameMethods()
    {
        return $this->hasMany(TestnameMethod::className(), ['testname_id' => 'testname_id']);
    }
}
