<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use common\models\referral\Courier;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Referraltracktesting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="referraltracktesting-form">

    <?php $form = ActiveForm::begin(); ?>
  <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'date_received_courier')->widget(DatePicker::classname(), [
             'options' => ['placeholder' => 'Select Date ...',
             'autocomplete'=>'off'],
             'type' => DatePicker::TYPE_COMPONENT_APPEND,
                 'pluginOptions' => [
                     'format' => 'yyyy-mm-dd',
                     'todayHighlight' => true,
                     'autoclose'=>true,
                     //'startDate' => date('Y-m-d'),
                     //'endDate' => date('Y-m-d')
                     
                 ]
             ]);
            ?>
        </div>
             
        <div class="col-sm-6">
            <?= $form->field($model, 'analysis_started')->widget(DatePicker::classname(), [
             'options' => ['placeholder' => 'Select Date ...',
             'autocomplete'=>'off'],
             'type' => DatePicker::TYPE_COMPONENT_APPEND,
                 'pluginOptions' => [
                     'format' => 'yyyy-mm-dd',
                     'todayHighlight' => true,
                     'autoclose'=>true,   
                 ]
             ]);
            ?>
        </div>
  </div>   
   <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'analysis_completed')->widget(DatePicker::classname(), [
             'options' => ['placeholder' => 'Select Date ...',
             'autocomplete'=>'off'],
             'type' => DatePicker::TYPE_COMPONENT_APPEND,
                 'pluginOptions' => [
                     'format' => 'yyyy-mm-dd',
                     'todayHighlight' => true,
                     'autoclose'=>true,  
                 ]
             ]);
            ?>
        </div>
        
        <div class="col-sm-6">
            <?= $form->field($model, 'cal_specimen_send_date')->widget(DatePicker::classname(), [
             'options' => ['placeholder' => 'Select Date ...',
             'autocomplete'=>'off'],
             'type' => DatePicker::TYPE_COMPONENT_APPEND,
                 'pluginOptions' => [
                     'format' => 'yyyy-mm-dd',
                     'todayHighlight' => true,
                     'autoclose'=>true,  
                 ]
             ]);
            ?>
        </div>
  </div>              
   
   <div class="row">
       <div class="col-sm-6">
           <?= $form->field($model, 'courier_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Courier::find()->all(), 'courier_id', 'name'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Select Courier ...'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
                ]);
            ?>
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
