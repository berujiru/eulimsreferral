<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Methodreference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="methodreference-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'method')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fee')->textInput() ?>

    <div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'create_time')->textInput(['readonly' => true]) ?>
    
    
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'update_time')->textInput(['readonly' => true]) ?>
    </div>
    </div>

    <div class="form-group pull-right">
   <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php if(Yii::$app->request->isAjax){ ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <?php } ?> 
	</div>

    <?php ActiveForm::end(); ?>

</div>
