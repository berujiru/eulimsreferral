<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\PstcrespondSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pstcrespond-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pstc_respond_id') ?>

    <?= $form->field($model, 'rstl_id') ?>

    <?= $form->field($model, 'pstc_id') ?>

    <?= $form->field($model, 'pstc_request_id') ?>

    <?= $form->field($model, 'request_ref_num') ?>

    <?php // echo $form->field($model, 'local_request_id') ?>

    <?php // echo $form->field($model, 'request_date_created') ?>

    <?php // echo $form->field($model, 'estimated_due_date') ?>

    <?php // echo $form->field($model, 'lab_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
