<?php

namespace common\models\referral;

use Yii;
use common\models\referral\Lab;
use common\models\referral\Sampletype;
/**
 * This is the model class for table "tbl_labsampletype".
 *
 * @property int $labsampletype_id
 * @property int $lab_id
 * @property int $sampletype_id
 * @property string $date_added
 * @property string $added_by
 */
class Labsampletype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_labsampletype';
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
            [['lab_id', 'sampletype_id', 'added_by'], 'required'],
            [['lab_id', 'sampletype_id'], 'integer'],
            [['date_added'], 'safe'],
            [['added_by'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'labsampletype_id' => 'Labsampletype ID',
            'lab_id' => 'Laboratory',
            'sampletype_id' => 'Sample Type',
            'date_added' => 'Date Added',
            'added_by' => 'Added By',
        ];
    }

    public function getLab()
    {
        return $this->hasOne(Lab::className(), ['lab_id' => 'lab_id']);
    }
    public function getSampletype()
    {
        return $this->hasOne(Sampletype::className(), ['sampletype_id' => 'sampletype_id']);
    }
}
