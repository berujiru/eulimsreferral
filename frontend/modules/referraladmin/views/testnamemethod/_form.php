<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\TestnameMethod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="testname-method-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'testname_id')->textInput() ?>

    <?= $form->field($model, 'methodreference_id')->textInput() ?>

    <?= $form->field($model, 'added_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
