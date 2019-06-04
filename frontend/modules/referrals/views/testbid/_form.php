<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Testbid */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="testbid-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bidder_agency_id')->textInput() ?>

    <?= $form->field($model, 'referral_id')->textInput() ?>

    <?= $form->field($model, 'bid_id')->textInput() ?>

    <?= $form->field($model, 'analysis_id')->textInput() ?>

    <?= $form->field($model, 'fee')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
