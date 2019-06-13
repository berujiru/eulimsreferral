<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Bid */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bid-form">

    <?php $form = ActiveForm::begin(['id'=>'test-bid-form']); ?>
	
	<?= Html::hiddenInput('referral_id', Yii::$app->request->get('referral_id')); ?>
	
	<?= Html::hiddenInput('analysis_id', Yii::$app->request->get('analysis_id')); ?>
	
	<div class="row">
		<div class="col-sm-12">
		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				[
					'attribute' => 'sample_id', 
					'label' => 'Sample Name',
					'value' => !empty($model->sample->sample_name) ? $model->sample->sample_name : null,
					'format' => 'raw',
					'labelColOptions' => ['style'=>'width:30%'],
				],
				[
					'attribute' => 'sample_id', 
					'label' => 'Sample Description',
					'value' => !empty($model->sample->description) ? $model->sample->description : null,
					'format' => 'raw',
					'labelColOptions' => ['style'=>'width:30%'],
				],
				[
					'attribute' => 'testname_id', 
					'label' => 'Test/Calibration',
					'value' => !empty($model->testname->test_name) ? $model->testname->test_name : null,
					'format' => 'raw',
					'labelColOptions' => ['style'=>'width:30%'],
				],
				[	'attribute' => 'methodreference_id',
					'label' => 'Method',
					'value' => !empty($model->methodreference->method) ? $model->methodreference->method : null,
					'format' => 'raw',
					'labelColOptions' => ['style'=>'width:30%'],
					//'valueColOptions'=>['style'=>'width:30%'],
					
				],
			],
		]) ?>
		</div>
		<!-- <div class="col-sm-6">
		<?= Html::label('Fee', 'analysis_fee') ?>
		<?= Html::textInput('analysis_fee',null); ?>
		</div> -->
		<div class="col-sm-6">
			<div class="form-group field-sample-samplename required">
				<label class="control-label" for="sample-samplename">Analysis Fee</label>
				<input type="text" id="analysis_fee" required="required" class="form-control" name="analysis_fee" maxlength="50" placeholder="Enter Fee" aria-required="true">
			</div>
			<div class="form-group">
				<?= Html::submitButton('Add', ['class' => 'btn btn-success','style'=>'float:right;','id'=>'btn-addbid']) ?>
			</div>
		</div>
	</div>

    <?php ActiveForm::end(); ?>


</div>
