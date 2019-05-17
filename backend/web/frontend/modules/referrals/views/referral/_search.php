<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\ReferralSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="referral-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'referral_id') ?>

    <?= $form->field($model, 'referral_code') ?>

    <?= $form->field($model, 'referral_date_time') ?>

    <?= $form->field($model, 'local_request_id') ?>

    <?= $form->field($model, 'receiving_agency_id') ?>

    <?php // echo $form->field($model, 'testing_agency_id') ?>

    <?php // echo $form->field($model, 'lab_id') ?>

    <?php // echo $form->field($model, 'sample_received_date') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'payment_type_id') ?>

    <?php // echo $form->field($model, 'modeofrelease_id') ?>

    <?php // echo $form->field($model, 'purpose_id') ?>

    <?php // echo $form->field($model, 'discount_id') ?>

    <?php // echo $form->field($model, 'discount_rate') ?>

    <?php // echo $form->field($model, 'total_fee') ?>

    <?php // echo $form->field($model, 'report_due') ?>

    <?php // echo $form->field($model, 'conforme') ?>

    <?php // echo $form->field($model, 'receiving_user_id') ?>

    <?php // echo $form->field($model, 'cro_receiving') ?>

    <?php // echo $form->field($model, 'testing_user_id') ?>

    <?php // echo $form->field($model, 'cro_testing') ?>

    <?php // echo $form->field($model, 'bid') ?>

    <?php // echo $form->field($model, 'cancelled') ?>

    <?php // echo $form->field($model, 'created_at_local') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
