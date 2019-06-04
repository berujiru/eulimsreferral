<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Bid */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bid-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sample_requirements')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'bid_amount')->textInput() ?>

    <?= $form->field($model, 'remarks')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'estimated_due')->widget(DatePicker::classname(), [
        'readonly'=>true,
        'options' => ['placeholder' => 'Report Due'],
            'value'=>function($model){
                 return date("m/d/Y",$model->estimated_due);
            },
        'pluginOptions' => [
                'autoclose' => true,
                'removeButton' => false,
                'format' => 'yyyy-mm-dd'
        ],
        'pluginEvents'=>[
            "change" => "",
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
