<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcrequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sample_received_date-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if($model->is_referral == 1): ?>
    <div id="for_sample_received_date" class="required">
        <?= $form->field($model, 'sample_received_date')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Enter Date'],
                'value'=>function($model){
                    return !empty($model->sample_received_date) ? date("m/d/Y h:i:s P", strtotime($model->sample_received_date)) : null;
                },
                'convertFormat' => true,
                'pluginOptions' => [
                        'autoclose' => true,
                        'removeButton' => false,
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'format' => 'php:Y-m-d H:i:s',
                        //'startDate'=>$RequestStartDate,
                ],
            ])->label('Sample Received Date'); ?>
    </div>

    <div class="form-group" style="padding-bottom: 3px;">
        <div style="float:right;">
            <?= Html::submitButton($model->isNewRecord ? 'Add' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-add-sample']) ?>
            <?= Html::button('Close', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
            <br>
        </div>
    </div>
    <?php endif; ?>
    <?php ActiveForm::end(); ?>

</div>
<?php
// Warning alert for no selected sample or method
echo Dialog::widget([
    'libName' => 'alertWarning', // a custom lib name
    'overrideYiiConfirm' => false,
    'options' => [  // customized BootstrapDialog options
        'size' => Dialog::SIZE_SMALL, // large dialog text
        'type' => Dialog::TYPE_DANGER, // bootstrap contextual color
        'title' => "<i class='glyphicon glyphicon-alert' style='font-size:20px'></i> Warning",
        'buttonLabel' => 'Close',
    ]
]);
?>
<script type="text/javascript">
    $('#btn-add-sample').on('click',function(e) {
        e.preventDefault();
        var sample_received = $('#pstcrequest-sample_received_date').val();

        if (!sample_received) {
            alertWarning.alert("<p class='text-danger' style='font-weight:bold;'>Sample received date cannot be blank! </p>");
            return false;
        } else {
            $('.sample_received_date-form form').submit();
        }
    });
</script>
