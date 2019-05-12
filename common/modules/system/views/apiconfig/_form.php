<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\system\Rstl;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\system\ApiSettings */
/* @var $form yii\widgets\ActiveForm */
$RstlList= ArrayHelper::map(Rstl::find()->all(),'rstl_id','name');
?>

<div class="api-settings-form" style="margin-bottom: 10px">
    <?php $form = ActiveForm::begin([
        'id' => 'api-form',
        'enableAjaxValidation' => false,
    ]); ?>
    <div class="row">
        <div class="col-md-6">
           <?= $form->field($model, 'rstl_id')->widget(Select2::classname(), [
                'data' => $RstlList,
                'language' => 'en',
                'options' => ['placeholder' => 'Select RSTL'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
    <?= $form->field($model, 'api_url')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
    <?= $form->field($model, 'get_token_url')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
     <div class="row">
        <div class="col-md-12">
    <?= $form->field($model, 'request_token')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="control-label" for="created_at">Created At</label>
            <span class="form-control disabled" style="background-color: lightgray"><?= date('m/d/Y h:i A',$model->created_at) ?></span>
        </div>
        <div class="col-md-6">
            <label class="control-label" for="updated_at">Updated At</label>
            <span class="form-control disabled" style="background-color: lightgray"><?= date('m/d/Y h:i A',$model->updated_at) ?></span>
        </div>
    </div>
    <div class="row" style="float: right;padding-right: 15px;margin-top: 10px">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-danger']) ?>
        <?= Html::Button('Cancel', ['class' => 'btn btn-default','id'=>'modalCancel','data-dismiss'=>'modal']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
