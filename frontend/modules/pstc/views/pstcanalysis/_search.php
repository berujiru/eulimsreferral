<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\PstcanalysisSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pstcanalysis-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pstc_analysis_id') ?>

    <?= $form->field($model, 'pstc_sample_id') ?>

    <?= $form->field($model, 'rstl_id') ?>

    <?= $form->field($model, 'pstc_id') ?>

    <?= $form->field($model, 'testname_id') ?>

    <?php // echo $form->field($model, 'testname') ?>

    <?php // echo $form->field($model, 'method_id') ?>

    <?php // echo $form->field($model, 'method') ?>

    <?php // echo $form->field($model, 'reference') ?>

    <?php // echo $form->field($model, 'fee') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'is_package') ?>

    <?php // echo $form->field($model, 'is_package_name') ?>

    <?php // echo $form->field($model, 'local_analysis_id') ?>

    <?php // echo $form->field($model, 'local_sample_id') ?>

    <?php // echo $form->field($model, 'cancelled') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
