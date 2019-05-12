<?php

namespace common\models\referral;

use Yii;

/**
 * This is the model class for table "tbl_generatedrequest".
 *
 * @property int $generatedrequest_id
 * @property int $rstl_id
 * @property int $request_id
 * @property int $lab_id
 * @property int $year
 * @property int $number
 */
class Generatedrequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_generatedrequest';
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
            [['rstl_id', 'request_id', 'lab_id', 'year', 'number'], 'integer'],
            [['year', 'number'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'generatedrequest_id' => 'Generatedrequest ID',
            'rstl_id' => 'Rstl ID',
            'request_id' => 'Request ID',
            'lab_id' => 'Lab ID',
            'year' => 'Year',
            'number' => 'Number',
        ];
    }
}
