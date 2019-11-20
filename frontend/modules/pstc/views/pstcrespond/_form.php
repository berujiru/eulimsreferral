<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcrespond */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pstcrespond-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rstl_id')->textInput() ?>

    <?= $form->field($model, 'pstc_id')->textInput() ?>

    <?= $form->field($model, 'pstc_request_id')->textInput() ?>

    <?= $form->field($model, 'request_ref_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'local_request_id')->textInput() ?>

    <?= $form->field($model, 'request_date_created')->textInput() ?>

    <?= $form->field($model, 'estimated_due_date')->textInput() ?>

    <?= $form->field($model, 'lab_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
