<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\referral\Agency;
use kartik\select2\Select2;
use common\models\referral\Lab;
use common\models\referral\Pstc;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\modules\admin\models\form\Signup */

$this->title = Yii::t('rbac-admin', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
$agencyList = ArrayHelper::map(Agency::find()->all(),'agency_id','name');
$labList = ArrayHelper::map(Lab::find()->all(),'lab_id','labname');
$pstcList =  ArrayHelper::map(Pstc::find()->all(),'pstc_id','name');
?>
<div class="site-signup">
    <?php if(!$isModal){ ?>
    <?= $this->renderFile(__DIR__.'/../menu.php',['button'=>'user']); ?>
    <?php } ?>
    <?= Html::errorSummary($model)?>
    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
     <fieldset>
        <legend>Login Details</legend>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'username') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'email') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'password')->passwordInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'verifypassword')->passwordInput() ?>
            </div>
        </div>
     </fieldset>
    <fieldset>
        <legend>Profile Info</legend>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'lastname')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'firstname')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'designation')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'middleinitial')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            <?= $form->field($model, 'rstl_id')->widget(Select2::classname(), [
                'data' => $agencyList,
                'language' => 'en',
                'options' => ['placeholder' => 'Select RSTL'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            </div>
            <div class="col-md-6">
            <?= $form->field($model, 'lab_id')->widget(Select2::classname(), [
                'data' => $labList,
                'language' => 'en',
                'options' => ['placeholder' => 'Select Lab'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            </div>
        </div>
		<div class="row">
            <div class="col-md-6">
				<label>Check for PSTC Account</label> <input type="checkbox" id="for_pstc" name="is_check_pstc" value="1"  onclick="showPstc()">
            </div>
        </div>
		<div class="row">
			<div class="col-md-6" id="pstc-container" style="display:none;">
				<?php
					echo $form->field($model,'pstc_id')->widget(Select2::classname(),[
						'data' => $pstcList,
						'theme' => Select2::THEME_KRAJEE,
						'pluginOptions' => [
							'placeholder' => 'Select PSTC',
							'allowClear' => true,
						],
					])->label('');
				?>
            </div>
		</div>
		
    </fieldset>
    <div class="form-group" style="float: right">
        <?= Html::submitButton(Yii::t('rbac-admin', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button', 'id' => 'btn_signup']) ?>
        <?php if(Yii::$app->request->isAjax){ ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <?php } ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
function showPstc() {
  var checkBox = document.getElementById("for_pstc");
  var pstc_div = document.getElementById("pstc-container");
  if (checkBox.checked == true){
    pstc_div.style.display = "block";
  } else {
    pstc_div.style.display = "none";
  }
}

$('#btn_signup').on('click',function(){
	var checkPstc = $("#for_pstc:checked").val();
	var pstc = $('#signup-pstc_id').val();
	
	if($("#for_pstc").is(':checked') && pstc == '') {
		alert("Please select PSTC!");
		return false;
	} else {
		//$('.image-loader').addClass('img-loader');
		$('.site-signup form').submit();
		//$('.image-loader').removeClass('img-loader');
	}
});
</script>
