<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\PstcsampleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pstcsample-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pstc_sample_id') ?>

    <?= $form->field($model, 'pstc_request_id') ?>

    <?= $form->field($model, 'rstl_id') ?>

    <?= $form->field($model, 'pstc_id') ?>

    <?= $form->field($model, 'testcategory_id') ?>

    <?php // echo $form->field($model, 'sampletype_id') ?>

    <?php // echo $form->field($model, 'sample_name') ?>

    <?php // echo $form->field($model, 'sample_description') ?>

    <?php // echo $form->field($model, 'sample_code') ?>

    <?php // echo $form->field($model, 'local_sample_id') ?>

    <?php // echo $form->field($model, 'local_request_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
