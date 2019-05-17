<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Sample */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
if(count($sampletype) > 0){
    $data = $sampletype;
} else {
    //$data = array(''=>'No Barangay');
    $data = ['' => 'No Sampletype'] + $sampletype;
}
?>


<div class="sample-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,'test_category_id')->widget(Select2::classname(),[
                'data' => $testcategory,
                'theme' => Select2::THEME_KRAJEE,
                'options' => ['id'=>'sample-test_category_id'],
                'pluginOptions' => ['allowClear' => true,'placeholder' => 'Select Testcategory'],
        ])
    ?>

    <?= $form->field($model, 'sample_type_id')->widget(DepDrop::classname(), [
        'type'=>DepDrop::TYPE_SELECT2,
        'data'=>$sampletype,
        'options'=>['id'=>'sample-sample_type_id'],
        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
        'pluginOptions'=>[
            'depends'=>['sample-test_category_id'],
            'placeholder'=>'Select Sampletype',
            'url'=>Url::to(['/lab/sample/listsampletype']),
            'loadingText' => 'Loading Sampletype...',
        ]
    ])
    ?>

    <?php
        if($labId == 2){
            //echo $form->field($model, 'sampling_date')->textInput();
            echo DatePicker::widget([
                'name' => 'Sample[sampling_date]',
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'value' => $model->sampling_date ? date('m/d/Y', strtotime($model->sampling_date)) : date('m/d/Y'),
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'mm/dd/yyyy'
                ]
            ]);
        } else {
            echo "";
        }
    ?>

    <?= $form->field($model, 'samplename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
