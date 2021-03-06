<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\CustomerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'rstl_id') ?>

    <?= $form->field($model, 'customer_code') ?>

    <?= $form->field($model, 'customer_name') ?>

    <?= $form->field($model, 'classification_id') ?>

    <?php // echo $form->field($model, 'latitude') ?>

    <?php // echo $form->field($model, 'longitude') ?>

    <?php // echo $form->field($model, 'head') ?>

    <?php // echo $form->field($model, 'barangay_id') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'tel') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'customer_type_id') ?>

    <?php // echo $form->field($model, 'business_nature_id') ?>

    <?php // echo $form->field($model, 'industrytype_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'customer_old_id') ?>

    <?php // echo $form->field($model, 'Oldcolumn_municipalitycity_id') ?>

    <?php // echo $form->field($model, 'Oldcolumn_district') ?>

    <?php // echo $form->field($model, 'local_customer_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
