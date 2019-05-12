<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Referral */
/* @var $form yii\widgets\ActiveForm */

$rstl_id= Yii::$app->user->identity->profile->rstl_id;
// Check Whether previous date will be disabled
$TRequest=Request::find()->where(["DATE_FORMAT(`request_datetime`,'%Y-%m-%d')"=>date("Y-m-d"),'rstl_id'=>$rstl_id])->count();
if($TRequest>0){
    $RequestStartDate=date("Y-m-d");
}else{
    $RequestStartDate="";
}
?>

<div class="referral-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(RequestType::find()->all(),'request_type_id','request_type'),
            'language' => 'en',
            'options' => ['placeholder' => 'Select Purpose','disabled'=>$disabled],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Request Type'); ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'request_datetime')->widget(DateTimePicker::classname(), [
            'readonly'=>true,
            'disabled' => $disabled,
        'options' => ['placeholder' => 'Enter Date'],
            'value'=>function($model){
                 return date("m/d/Y h:i:s P", strtotime($model->request_datetime));
            },
            'convertFormat' => true,
        'pluginOptions' => [
                'autoclose' => true,
                'removeButton' => false,
                'todayHighlight' => true,
                'todayBtn' => true,
                'format' => 'php:Y-m-d H:i:s',
                'startDate'=>$RequestStartDate,
        ],
            'pluginEvents'=>[
                "changeDate" => "function(e) { 
                    var dv=$('#erequest-request_datetime').val();
                    var d=dv.split(' ');
                    $('#erequest-request_date').val(d[0]);
                }",
            ]
        ])->label('Request Date'); ?>
        </div>
    </div>

    <div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(RequestType::find()->all(),'request_type_id','request_type'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Purpose','disabled'=>$disabled],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Request Type'); ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'request_datetime')->widget(DateTimePicker::classname(), [
        'readonly'=>true,
        'disabled' => $disabled,
    'options' => ['placeholder' => 'Enter Date'],
        'value'=>function($model){
             return date("m/d/Y h:i:s P", strtotime($model->request_datetime));
        },
        'convertFormat' => true,
    'pluginOptions' => [
            'autoclose' => true,
            'removeButton' => false,
            'todayHighlight' => true,
            'todayBtn' => true,
            'format' => 'php:Y-m-d H:i:s',
            'startDate'=>$RequestStartDate,
    ],
        'pluginEvents'=>[
            "changeDate" => "function(e) { 
                var dv=$('#erequest-request_datetime').val();
                var d=dv.split(' ');
                $('#erequest-request_date').val(d[0]);
            }",
        ]
    ])->label('Request Date'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(RequestType::find()->all(),'request_type_id','request_type'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Purpose','disabled'=>$disabled],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Request Type'); ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'request_datetime')->widget(DateTimePicker::classname(), [
        'readonly'=>true,
        'disabled' => $disabled,
    'options' => ['placeholder' => 'Enter Date'],
        'value'=>function($model){
             return date("m/d/Y h:i:s P", strtotime($model->request_datetime));
        },
        'convertFormat' => true,
    'pluginOptions' => [
            'autoclose' => true,
            'removeButton' => false,
            'todayHighlight' => true,
            'todayBtn' => true,
            'format' => 'php:Y-m-d H:i:s',
            'startDate'=>$RequestStartDate,
    ],
        'pluginEvents'=>[
            "changeDate" => "function(e) { 
                var dv=$('#erequest-request_datetime').val();
                var d=dv.split(' ');
                $('#erequest-request_date').val(d[0]);
            }",
        ]
    ])->label('Request Date'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(RequestType::find()->all(),'request_type_id','request_type'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Purpose','disabled'=>$disabled],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Request Type'); ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'request_datetime')->widget(DateTimePicker::classname(), [
        'readonly'=>true,
        'disabled' => $disabled,
    'options' => ['placeholder' => 'Enter Date'],
        'value'=>function($model){
             return date("m/d/Y h:i:s P", strtotime($model->request_datetime));
        },
        'convertFormat' => true,
    'pluginOptions' => [
            'autoclose' => true,
            'removeButton' => false,
            'todayHighlight' => true,
            'todayBtn' => true,
            'format' => 'php:Y-m-d H:i:s',
            'startDate'=>$RequestStartDate,
    ],
        'pluginEvents'=>[
            "changeDate" => "function(e) { 
                var dv=$('#erequest-request_datetime').val();
                var d=dv.split(' ');
                $('#erequest-request_date').val(d[0]);
            }",
        ]
    ])->label('Request Date'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'request_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(RequestType::find()->all(),'request_type_id','request_type'),
        'language' => 'en',
        'options' => ['placeholder' => 'Select Purpose','disabled'=>$disabled],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Request Type'); ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'request_datetime')->widget(DateTimePicker::classname(), [
        'readonly'=>true,
        'disabled' => $disabled,
    'options' => ['placeholder' => 'Enter Date'],
        'value'=>function($model){
             return date("m/d/Y h:i:s P", strtotime($model->request_datetime));
        },
        'convertFormat' => true,
    'pluginOptions' => [
            'autoclose' => true,
            'removeButton' => false,
            'todayHighlight' => true,
            'todayBtn' => true,
            'format' => 'php:Y-m-d H:i:s',
            'startDate'=>$RequestStartDate,
    ],
        'pluginEvents'=>[
            "changeDate" => "function(e) { 
                var dv=$('#erequest-request_datetime').val();
                var d=dv.split(' ');
                $('#erequest-request_date').val(d[0]);
            }",
        ]
    ])->label('Request Date'); ?>
    </div>
</div>

<!--
    <?= $form->field($model, 'referral_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'referral_date')->textInput() ?>

    <?= $form->field($model, 'referral_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiving_agency_id')->textInput() ?>

    <?= $form->field($model, 'testing_agency_id')->textInput() ?>

    <?= $form->field($model, 'lab_id')->textInput() ?>

    <?= $form->field($model, 'sample_received_date')->textInput() ?>

    <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'payment_type_id')->textInput() ?>

    <?= $form->field($model, 'modeofrelease_id')->textInput() ?>

    <?= $form->field($model, 'purpose_id')->textInput() ?>

    <?= $form->field($model, 'discount_id')->textInput() ?>

    <?= $form->field($model, 'discount_amt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_fee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'report_due')->textInput() ?>

    <?= $form->field($model, 'conforme')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'received_by')->textInput() ?>

    <?= $form->field($model, 'bid')->textInput() ?>

    <?= $form->field($model, 'cancelled')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>
-->
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
