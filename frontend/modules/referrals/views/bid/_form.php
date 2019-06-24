<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Bid */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$bid_requirement = Yii::$app->session->get('addbid_requirement_'.$referralId);

if(isset($bid_requirement) && count($bid_requirement) > 0){
	$sample_requirements = ($bid_requirement['sample_requirements']);
	$remarks = ($bid_requirement['remarks']);
	$estimated_due = $bid_requirement['estimated_due'];
} else {
	$sample_requirements = null;
	$remarks = null;
	$estimated_due = null;
}
?>

<div class="bid-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= Html::hiddenInput('referral_id', Yii::$app->request->get('referral_id')); ?>

    <?= $form->field($model, 'sample_requirements')->textarea(['rows' => 3, 'value'=>$sample_requirements,'placeholder'=>'Enter sample requirement']) ?>

    <?= $form->field($model, 'remarks')->textarea(['maxlength' => true, 'value'=>$remarks, 'placeholder'=>'Enter remarks']) ?>

    <?= $form->field($model, 'estimated_due')->widget(DatePicker::classname(), [
        'readonly'=>true,
        'options' => ['placeholder' => 'Report Due'],
            'value'=>function($model){
                return date("Y-m-d",$model->estimated_due);
            },
        'pluginOptions' => [
                'autoclose' => true,
                'removeButton' => false,
                'format' => 'yyyy-mm-dd'
        ],
        'pluginEvents'=>[
            "change" => "",
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
