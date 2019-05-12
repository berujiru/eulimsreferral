<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "tbl_log_sync".
 *
 * @property int $logsync_id
 * @property string $tblname
 * @property int $recordID
 * @property string $datetime
 * @property int $rstl_id
 * @property int $user_id
 */
class LogSync extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_log_sync';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tblname', 'recordID', 'rstl_id', 'user_id'], 'required'],
            [['recordID', 'rstl_id', 'user_id'], 'integer'],
            [['datetime'], 'safe'],
            [['tblname'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'logsync_id' => 'Logsync ID',
            'tblname' => 'Tblname',
            'recordID' => 'Record ID',
            'datetime' => 'Datetime',
            'rstl_id' => 'Rstl ID',
            'user_id' => 'User ID',
        ];
    }
}
