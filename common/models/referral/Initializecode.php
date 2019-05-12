<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_initializecode".
 *
 * @property int $initializecode_id
 * @property int $rstl_id
 * @property int $lab_id
 * @property int $code_type
 * @property int $start_code
 * @property int $active
 *
 * @property Lab $lab
 */
class Initializecode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_initializecode';
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
            [['rstl_id', 'lab_id', 'code_type', 'start_code', 'active'], 'required'],
            [['rstl_id', 'lab_id', 'code_type', 'start_code', 'active'], 'integer'],
            [['lab_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lab::className(), 'targetAttribute' => ['lab_id' => 'lab_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'initializecode_id' => 'Initializecode ID',
            'rstl_id' => 'Rstl ID',
            'lab_id' => 'Lab ID',
            'code_type' => 'Code Type',
            'start_code' => 'Start Code',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLab()
    {
        return $this->hasOne(Lab::className(), ['lab_id' => 'lab_id']);
    }
}
