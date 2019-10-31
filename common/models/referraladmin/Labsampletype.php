<?php

namespace common\models\referraladmin;

use Yii;

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
            'lab_id' => 'Lab ID',
            'sampletype_id' => 'Sampletype ID',
            'date_added' => 'Date Added',
            'added_by' => 'Added By',
        ];
    }
}
