<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_loginlogs".
 *
 * @property int $loginlogs_id
 * @property int $user_id
 * @property int $agency_id
 * @property string $login_date
 */
class Loginlogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_loginlogs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'agency_id', 'login_date'], 'required'],
            [['user_id', 'agency_id', 'backend'], 'integer'],
            [['login_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'loginlogs_id' => 'Loginlogs ID',
            'user_id' => 'User ID',
            'agency_id' => 'Agency ID',
            'login_date' => 'Login Date',
			'backend' => 'Backend',
        ];
    }
}
