<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_profile".
 *
 * @property int $profile_id
 * @property int $user_id
 * @property string $lastname
 * @property string $firstname
 * @property string $fullname
 * @property string $designation
 * @property string $middleinitial
 * @property int $rstl_id
 * @property int $lab_id
 * @property string $contact_numbers
 * @property string $image_url
 * @property string $avatar
 * @property string $designation_unit
 *
 * @property User $user
 * @property Rstl $rstl
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_profile';
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
            [['user_id', 'lastname', 'firstname', 'designation', 'rstl_id', 'lab_id'], 'required'],
            [['user_id', 'rstl_id', 'lab_id'], 'integer'],
            [['lastname', 'firstname', 'designation', 'middleinitial', 'designation_unit'], 'string', 'max' => 50],
            [['fullname', 'contact_numbers', 'image_url', 'avatar'], 'string', 'max' => 100],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['rstl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rstl::className(), 'targetAttribute' => ['rstl_id' => 'rstl_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => 'Profile ID',
            'user_id' => 'User ID',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
            'fullname' => 'Fullname',
            'designation' => 'Designation',
            'middleinitial' => 'Middleinitial',
            'rstl_id' => 'Rstl ID',
            'lab_id' => 'Lab ID',
            'contact_numbers' => 'Contact Numbers',
            'image_url' => 'Image Url',
            'avatar' => 'Avatar',
            'designation_unit' => 'Designation Unit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRstl()
    {
        return $this->hasOne(Rstl::className(), ['rstl_id' => 'rstl_id']);
    }
}
