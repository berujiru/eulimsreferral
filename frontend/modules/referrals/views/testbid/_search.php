<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\TestbidSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="testbid-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'test_bid_id') ?>

    <?= $form->field($model, 'bidder_agency_id') ?>

    <?= $form->field($model, 'referral_id') ?>

    <?= $form->field($model, 'bid_id') ?>

    <?= $form->field($model, 'analysis_id') ?>

    <?php // echo $form->field($model, 'fee') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
