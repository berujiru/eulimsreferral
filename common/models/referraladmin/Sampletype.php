<?php

namespace common\models\referraladmin;

use Yii;

/**
 * This is the model class for table "tbl_sampletype".
 *
 * @property int $sampletype_id
 * @property string $type
 * @property int $status_id
 *
 * @property Sample[] $samples
 * @property Sampletypetestname[] $sampletypetestnames
 */
class Sampletype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_sampletype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['status_id'], 'integer'],
            [['type'], 'string', 'max' => 75],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sampletype_id' => 'Sampletype ID',
            'type' => 'Type',
            'status_id' => 'Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSamples()
    {
        return $this->hasMany(Sample::className(), ['sample_type_id' => 'sampletype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampletypetestnames()
    {
        return $this->hasMany(Sampletypetestname::className(), ['sampletype_id' => 'sampletype_id']);
    }
}
