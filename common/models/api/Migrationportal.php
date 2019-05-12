<?php

namespace common\models\api;

use Yii;

/**
 * This is the model class for table "tbl_migrationportal".
 *
 * @property int $pm_id
 * @property string $date_migrated  //this means the date last the data has migrated
 * @property int $record_id   //this does not refer to the pk of the rec but the counter to where did the migration has stopped
 * @property string $table_name
 * @property int $record_idscript 
 */
class Migrationportal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_migrationportal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_migrated', 'record_id', 'table_name'], 'required'],
            [['date_migrated'], 'safe'],
            [['record_id', 'record_idscript'], 'integer'],
            [['table_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pm_id' => 'Pm ID',
            'date_migrated' => 'Date Migrated',
            'record_id' => 'Record ID',
            'table_name' => 'Table Name',
            'record_idscript' => 'Record Idscript', 
        ];
    }
}
