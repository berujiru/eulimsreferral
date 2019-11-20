<?php

namespace common\models\referral;

use Yii;

use common\models\lab\Testname;
use common\models\lab\Methodreference;

/**
 * This is the model class for table "tbl_pstcanalysis".
 *
 * @property int $pstc_analysis_id
 * @property int $pstc_sample_id
 * @property int $rstl_id
 * @property int $pstc_id
 * @property int $testname_id
 * @property string $testname
 * @property int $method_id
 * @property string $method
 * @property string $reference
 * @property string $fee
 * @property int $testcategory_id
 * @property int $sampletype_id
 * @property int $quantity
 * @property int $is_package
 * @property int $is_package_name
 * @property int $local_analysis_id
 * @property int $local_sample_id
 * @property int $cancelled
 * @property string $created_at
 * @property string $updated_at
 */
class Pstcanalysis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_pstcanalysis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pstc_sample_id', 'rstl_id', 'pstc_id', 'testname_id', 'testname', 'method_id', 'method', 'reference', 'fee', 'created_at'], 'required'],
            [['pstc_sample_id', 'rstl_id', 'pstc_id', 'testname_id', 'package_id', 'method_id', 'testcategory_id', 'sampletype_id', 'quantity', 'is_package', 'is_package_name', 'local_analysis_id', 'local_sample_id', 'cancelled','analysis_offered'], 'integer'],
            [['reference'], 'string'],
            [['fee'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['package_name'], 'string', 'max' => 150],
            [['testname', 'method'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pstc_analysis_id' => 'Pstc Analysis ID',
            'pstc_sample_id' => 'Pstc Sample ID',
            'rstl_id' => 'Rstl ID',
            'pstc_id' => 'Pstc ID',
            'testname_id' => 'Testname ID',
            'testname' => 'Testname',
            'package_id' => 'Package ID',
            'package_name' => 'Package Name',
            'method_id' => 'Method ID',
            'method' => 'Method',
            'reference' => 'Reference',
            'fee' => 'Fee',
            'testcategory_id' => 'Testcategory ID',
            'sampletype_id' => 'Sampletype ID',
            'quantity' => 'Quantity',
            'is_package' => 'Is Package',
            'is_package_name' => 'Is Package Name',
            'local_analysis_id' => 'Local Analysis ID',
            'local_sample_id' => 'Local Sample ID',
			'analysis_offered' => 'Analysis Offered',
            'cancelled' => 'Cancelled',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getSample()
    {
        return $this->hasOne(Pstcsample::className(), ['pstc_sample_id' => 'pstc_sample_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTestnames()
    {
        return $this->hasOne(\common\models\lab\Testname::className(), ['testname_id' => 'testname_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getMethodrefs()
    {
        return $this->hasOne(\common\models\lab\Methodreference::className(), ['method_reference_id' => 'method_id']);
    }
}
