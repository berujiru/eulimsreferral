<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Analysis */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="analysis-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'analysis_type_id')->textInput() ?>

    <?= $form->field($model, 'date_analysis')->textInput() ?>

    <?= $form->field($model, 'agency_id')->textInput() ?>

    <?= $form->field($model, 'pstcanalysis_id')->textInput() ?>

    <?= $form->field($model, 'sample_id')->textInput() ?>

    <?= $form->field($model, 'testname_id')->textInput() ?>

    <?= $form->field($model, 'methodreference_id')->textInput() ?>

    <?= $form->field($model, 'analysis_fee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cancelled')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
