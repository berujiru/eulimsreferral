<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_attachment".
 *
 * @property int $attachment_id
 * @property string $filename
 * @property int $attachment_type 1=OR, 2=Receipt, 3=Test Result
 * @property int $referral_id
 * @property int $uploadedby_user_id
 * @property string $uploadedby_name
 * @property string $upload_date
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_attachment';
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
            [['filename', 'attachment_type', 'referral_id', 'uploadedby_user_id', 'uploadedby_name', 'upload_date'], 'required'],
            [['attachment_type', 'referral_id', 'uploadedby_user_id'], 'integer'],
            [['upload_date'], 'safe'],
            [['filename'], 'string', 'max' => 400],
            [['uploadedby_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attachment_id' => 'Attachment ID',
            'filename' => 'Filename',
            'attachment_type' => 'Attachment Type',
            'referral_id' => 'Referral ID',
            'uploadedby_user_id' => 'Uploadedby User ID',
            'uploadedby_name' => 'Uploadedby Name',
            'upload_date' => 'Upload Date',
        ];
    }
}
