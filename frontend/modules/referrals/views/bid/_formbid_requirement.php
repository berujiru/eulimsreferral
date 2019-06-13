<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Bid */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bid-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= Html::hiddenInput('referral_id', Yii::$app->request->get('referral_id')); ?>
	
	<?= Html::label('Fee', 'analysis_fee') ?>
	
	<?= Html::label('Fee', 'analysis_fee') ?>
	
	<?= Html::label('Fee', 'analysis_fee') ?>
	
    <?= Html::textInput('analysis_fee',null); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
