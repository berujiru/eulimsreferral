<?php

namespace common\models\lab;

use Yii;

/**
 * This is the model class for table "tbl_testname_method".
 *
 * @property int $testname_method_id
 * @property int $testname_id
 * @property int $method_id
 * @property string $create_time
 * @property string $update_time
 *
 * @property Testname $testname
 */
class Testnamemethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_testname_method';
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
            [['testname_id', 'method_id'], 'required'],
            [['testname_id', 'method_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['testname_id'], 'exist', 'skipOnError' => true, 'targetClass' => Testname::className(), 'targetAttribute' => ['testname_id' => 'testname_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'testname_method_id' => 'Testname Method ID',
            'testname_id' => 'Test Name',
            'method_id' => 'Method',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestname()
    {
        return $this->hasOne(Testname::className(), ['testname_id' => 'testname_id']);
    }

    public function getMethod()
    {
        return $this->hasOne(Methodreference::className(), ['method_reference_id' => 'method_id']);
    }
}
