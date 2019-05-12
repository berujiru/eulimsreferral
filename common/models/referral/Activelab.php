<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_activelab".
 *
 * @property int $activelab_id
 * @property int $agency_id
 * @property int $lab_id
 * @property int $active
 */
class Activelab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_activelab';
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
            [['agency_id', 'lab_id'], 'required'],
            [['agency_id', 'lab_id', 'active'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'activelab_id' => 'Activelab ID',
            'agency_id' => 'Agency ID',
            'lab_id' => 'Lab ID',
            'active' => 'Active',
        ];
    }
}
