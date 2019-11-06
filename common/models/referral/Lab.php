<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_lab".
 *
 * @property int $lab_id
 * @property string $labname
 * @property string $labcode
 * @property int $active
 *
 * @property Initializecode[] $initializecodes
 * @property Packagelist[] $packagelists
 * @property Referral[] $referrals
 * @property Referralcode[] $referralcodes
 */
class Lab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_lab';
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
            [['labname', 'labcode'], 'required'],
            [['active'], 'integer'],
            [['labname'], 'string', 'max' => 50],
            [['labcode'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lab_id' => 'Lab ID',
            'labname' => 'Laboratory Name',
            'labcode' => 'Laboratory Code',
            'active' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInitializecodes()
    {
        return $this->hasMany(Initializecode::className(), ['lab_id' => 'lab_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagelists()
    {
        return $this->hasMany(Packagelist::className(), ['lab_id' => 'lab_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferrals()
    {
        return $this->hasMany(Referral::className(), ['lab_id' => 'lab_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferralcodes()
    {
        return $this->hasMany(Referralcode::className(), ['lab_id' => 'lab_id']);
    }
}
