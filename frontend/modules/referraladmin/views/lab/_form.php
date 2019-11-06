<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Lab */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lab-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'labname')->textInput(['maxlength' => true])->label('Laboratory Name') ?>

    <?= $form->field($model, 'labcode')->textInput(['maxlength' => true])->label('Laboratory Code') ?>

    <?= $form->field($model,'active')->widget(Select2::classname(),[
                    'data' => ['1'=>'Active', '0'=>'Inactive'],
                    'theme' => Select2::THEME_KRAJEE,
                    'options' => ['id'=>'active_id'],
                    'pluginOptions' => ['allowClear' => true],
            ])
    ?>


    <div class="form-group pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(Yii::$app->request->isAjax){ ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
