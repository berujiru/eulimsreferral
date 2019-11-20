<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\VisitedpageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visitedpage-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'visited_page_id') ?>

    <?= $form->field($model, 'home_url') ?>

    <?= $form->field($model, 'module') ?>

    <?= $form->field($model, 'controller') ?>

    <?= $form->field($model, 'action') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'rstl_id') ?>

    <?php // echo $form->field($model, 'pstc_id') ?>

    <?php // echo $form->field($model, 'date_visited') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
