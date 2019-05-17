<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Referral */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="referral-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'referral_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'referral_date_time')->textInput() ?>

    <?= $form->field($model, 'local_request_id')->textInput() ?>

    <?= $form->field($model, 'receiving_agency_id')->textInput() ?>

    <?= $form->field($model, 'testing_agency_id')->textInput() ?>

    <?= $form->field($model, 'lab_id')->textInput() ?>

    <?= $form->field($model, 'sample_received_date')->textInput() ?>

    <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'payment_type_id')->textInput() ?>

    <?= $form->field($model, 'modeofrelease_id')->textInput() ?>

    <?= $form->field($model, 'purpose_id')->textInput() ?>

    <?= $form->field($model, 'discount_id')->textInput() ?>

    <?= $form->field($model, 'discount_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_fee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'report_due')->textInput() ?>

    <?= $form->field($model, 'conforme')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiving_user_id')->textInput() ?>

    <?= $form->field($model, 'cro_receiving')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'testing_user_id')->textInput() ?>

    <?= $form->field($model, 'cro_testing')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bid')->textInput() ?>

    <?= $form->field($model, 'cancelled')->textInput() ?>

    <?= $form->field($model, 'created_at_local')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
