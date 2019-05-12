<?php

namespace common\models\api;

use Yii;

/**
 * This is the model class for table "tbl_yii_migration".
 *
 * @property int $id
 * @property string $tblname
 * @property string $num
 * @property string $ids
 */
class YiiMigration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_yii_migration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tblname', 'num'], 'required'],
            [['tblname'], 'string', 'max' => 50],
            [['num'], 'string', 'max' => 100],
            [['ids'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tblname' => 'Table name',
            'num' => 'Last ID No.:',
            'ids' => 'Ids',
        ];
    }
}
