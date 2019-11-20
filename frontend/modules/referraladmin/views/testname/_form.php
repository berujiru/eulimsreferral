<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Testname */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="testname-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'test_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'active')->widget(Select2::classname(),[
                    'data' => ['1'=>'Active', '0'=>'Inactive'],
                    'theme' => Select2::THEME_KRAJEE,
                    'options' => ['id'=>'sample-testcategory_id'],
                    'pluginOptions' => ['allowClear' => true],
            ])->label('Status');
    ?>

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
