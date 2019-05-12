<?php

namespace common\models\system;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_api_settings".
 *
 * @property int $api_settings_id
 * @property int $rstl_id
 * @property string $api_url
 * @property string $get_token_url
 * @property string $request_token
 * @property int $created_at
 * @property int $updated_at
 * 
 * @property Rstl $rstl
 */
class ApiSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_api_settings';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rstl_id', 'api_url', 'get_token_url', 'request_token'], 'required'],
            [['rstl_id', 'created_at', 'updated_at'], 'integer'],
            [['api_url', 'get_token_url', 'request_token'], 'string', 'max' => 100],
            ['rstl_id', 'unique', 'targetAttribute' => 'rstl_id'],
            [['rstl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rstl::className(), 'targetAttribute' => ['rstl_id' => 'rstl_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'api_settings_id' => 'Api Settings ID',
            'rstl_id' => 'RSTL',
            'api_url' => 'Api Url',
            'get_token_url' => 'Get Token Url',
            'request_token' => 'Request Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getRstl()
    {
        return $this->hasOne(Rstl::className(), ['rstl_id' => 'rstl_id']);
    }
}
