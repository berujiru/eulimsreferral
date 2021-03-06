<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_configlab".
 *
 * @property int $configlab_id
 * @property int $rstl_id
 * @property string $lab
 */
class Configlab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_configlab';
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
            [['rstl_id'], 'required'],
            [['rstl_id'], 'integer'],
            [['lab'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'configlab_id' => 'Configlab ID',
            'rstl_id' => 'Rstl ID',
            'lab' => 'Lab',
        ];
    }
}
