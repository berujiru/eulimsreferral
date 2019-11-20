<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\LoginlogsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="loginlogs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'loginlogs_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'agency_id') ?>

    <?= $form->field($model, 'login_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
