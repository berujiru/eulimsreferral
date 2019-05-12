<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\system\User;
use yii\helpers\ArrayHelper;
use common\models\system\Rstl;
use common\models\lab\Lab;
use cozumel\cropper\ImageCropper;
use kartik\widgets\FileInput;
use yii\web\View;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Profile */
/* @var $form yii\widgets\ActiveForm */
if(Yii::$app->user->can('profile-full-access')){
    $UserList= ArrayHelper::map(User::find()->all(),'user_id','email');
}else{ 
    $UserList= ArrayHelper::map(User::findAll(['user_id'=>Yii::$app->user->identity->user_id]),'user_id','email');
}
$RstlList= ArrayHelper::map(Rstl::find()->all(),'rstl_id','name');
$LabList= ArrayHelper::map(Lab::find()->all(),'lab_id','labname');
$js =<<< SCRIPT
   $('#profileImage_upload').on('fileclear', function(event) {
      $('#profile-image_url').val('');
      $('#profile-avatar').val('');
   });      
SCRIPT;
$this->registerJs($js, View::POS_READY);
$this->registerCssFile("/css/profile.css");
Yii::$app->params['uploadUrl']="/uploads/user/photo/";
?>

<div class="profile-form">
<?php $form = ActiveForm::begin([
    'options'=>['enctype'=>'multipart/form-data']
]); ?>
<?php echo $form->field($model, 'image_url')->hiddenInput(['value' => $model->image_url])->label(false) ?>
<?php echo $form->field($model, 'avatar')->hiddenInput(['value' => $model->avatar])->label(false) ?>
    <div class="row ">
        <div class="col-md-12"> 
            <?php // your fileinput widget for single file upload
            echo $form->field($model, 'image')->widget(FileInput::classname(), [
                'options'=>[
                    'id'=>'profileImage_upload',
                    'accept'=>'image/*'
                ],
                'pluginOptions'=>[
                    'allowedFileExtensions'=>['jpg','gif','png'],
                    'overwriteInitial'=>true,
                    'resizeImages'=>true,
                    'initialPreviewConfig'=>[
                        'width'=>'120px',
                    ],
                    'initialPreview' => [
                        '<img src="'.Yii::$app->params['uploadUrl'].$model->getImageUrl().'" width="200" class="file-preview-image">',
                    ],
                    'showUpload'=>false,
                    'showRemove'=>false,
                    'showBrowse'=>true,
                    'showText'=>false
                ],
            ])->label(false);
            ?>   
        </div>
    </div>        
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                'data' => $UserList,
                'language' => 'en',
                'options' => ['placeholder' => 'Select User'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label("Username/Email"); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'middleinitial')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'designation')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'contact_numbers')->textInput() ?>
        </div>
    </div>
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
            <?= $form->field($model, 'lab_id')->widget(Select2::classname(), [
                'data' => $LabList,
                'language' => 'en',
                'options' => ['placeholder' => 'Select Lab'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>
    <div class="row" style="float: right;padding-right: 15px">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-danger']) ?>
        <?= Html::Button('Cancel', ['class' => 'btn btn-default','id'=>'modalCancel','data-dismiss'=>'modal']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>
