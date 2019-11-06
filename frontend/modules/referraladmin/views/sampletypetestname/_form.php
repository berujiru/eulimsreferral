<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Sampletypetestname */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sampletypetestname-form">

    <?php $form = ActiveForm::begin(); ?>

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

    <div class="input-group">
        <?= $form->field($model,'testname_id')->widget(Select2::classname(),[
                    'data' => $testnamelist,
                    'theme' => Select2::THEME_KRAJEE,
                    'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Test Name'],
            ])
        ?>
        <span class="input-group-btn" style="padding-top: 25.5px">
                <button onclick="LoadModal('Create New Test Name', '/referraladmin/testname/create');"class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
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
