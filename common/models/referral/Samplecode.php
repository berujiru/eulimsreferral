<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_samplecode".
 *
 * @property int $samplecode_id
 * @property int $referral_id
 * @property string $referral_code
 * @property int $sample_id
 * @property int $lab_id
 * @property int $number
 * @property int $year
 * @property int $agency_id
 * @property int $cancelled
 */
class Samplecode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_samplecode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referral_id', 'sample_id', 'lab_id', 'number', 'year'], 'required'],
            [['referral_id', 'sample_id', 'lab_id', 'number', 'year', 'agency_id', 'cancelled'], 'integer'],
            [['referral_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'samplecode_id' => 'Samplecode ID',
            'referral_id' => 'Referral ID',
            'referral_code' => 'Referral Code',
            'sample_id' => 'Sample ID',
            'lab_id' => 'Lab ID',
            'number' => 'Number',
            'year' => 'Year',
            'agency_id' => 'Agency ID',
            'cancelled' => 'Cancelled',
        ];
    }
}
