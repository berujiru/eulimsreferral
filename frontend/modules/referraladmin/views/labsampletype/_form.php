<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\LabSampletype */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="lab-sampletype-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="input-group">
        <?= $form->field($model,'lab_id')->widget(Select2::classname(),[
            'data' => $lablist,
            'theme' => Select2::THEME_KRAJEE,
            'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Lab'],
        ])
        ?>
        <span class="input-group-btn" style="padding-top: 25.5px">
            <button onclick="LoadModal('Create Laboratory', '/referraladmin/lab/create');"class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
        </span>
    </div><br/>
    <div class="input-group">
        <?= $form->field($model,'sampletype_id')->widget(Select2::classname(),[
                'data' => $sampletypelist,
                'theme' => Select2::THEME_KRAJEE,
                'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Sample Type'],
            ])
        ?>
        <span class="input-group-btn" style="padding-top: 25.5px">
            <button onclick="LoadModal('Create New Sample Type', '/referraladmin/sampletype/create');"class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
        </span>
    </div><br/>
    <div>
        <?= $form->field($model, 'added_by')->textInput(['readonly' => true]) ?>
    </div>
    <div class="form-group pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(Yii::$app->request->isAjax){ ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
