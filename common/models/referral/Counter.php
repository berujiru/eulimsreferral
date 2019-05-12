<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_counter".
 *
 * @property int $counter_id
 * @property string $name
 * @property int $number
 */
class Counter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_counter';
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
            [['name', 'number'], 'required'],
            [['number'], 'integer'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'counter_id' => 'Counter ID',
            'name' => 'Name',
            'number' => 'Number',
        ];
    }
}
