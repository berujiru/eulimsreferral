

<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\referral\Referral;

$this->title = 'Referrals';
$this->params['breadcrumbs'][] = $this->title;
/*print_r($model);
exit; */
?>
<div class="referral-tracking">
      <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
            
             <?php 
                echo '<label>Referral Code</label>';
                echo Select2::widget([
                'name' => 'referral_code',
                'data' => ArrayHelper::map(Referral::find()->where(['not', ['referral_code' => NULL]])->orderBy(['referral_code'=>SORT_ASC])->all(), 'referral_code', 'referral_code'),
                'options' => ['placeholder' => 'Select code'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
             
             ?>
        </div>
         <div class="col-sm-3">
              <?php
                echo '<label>From</label>';
                echo DatePicker::widget([
                        'name' => 'from_date', 
                        'value' => date('Y-M-d'),
                        'options' => ['placeholder' => 'Select Date'],
                        'pluginOptions' => [
                                'format' => 'yyyy-M-dd',
                                'todayHighlight' => true
                        ]
                ]);
              ?>
        </div>
        <div class="col-sm-3">
              <?php
                echo '<label>To</label>';
                echo DatePicker::widget([
                        'name' => 'to_date', 
                        'value' => date('Y-M-d'),
                        'options' => ['placeholder' => 'Select Date'],
                        'pluginOptions' => [
                                'format' => 'yyyy-M-dd',
                                'todayHighlight' => true
                        ]
                ]);
              ?>
        </div>
        <div class="col-sm-3">
            <br><?=Html::submitButton('<span class="glyphicon glyphicon-download"></span> Export', ['class' => 'btn btn-success']) ?>
        </div>
        
    </div>
    <div class="row">
        <div class="col-lg-12">  
             <div id="prog" style="position:relative;display:none;">
                <img style="display:block; margin:0 auto;" src="<?php echo  $GLOBALS['frontend_base_uri']; ?>/images/ajax-loader.gif">
                 </div>


            <div id="monitorings" style="padding:0px!important;">    	
              
            </div> 

        </div>
    </div> 
    <?php ActiveForm::end(); ?>
   
</div>
<script type="text/javascript">
    $('#w1').on('change',function(e) {
        var referral_code=$(this).val();
        var date_from=$('#w2').val();
        var date_to=$('#w3').val();
        //alert(date_to);
        jQuery.ajax( {
            type: 'POST',
            url: '/referrals/referraltracking/search?rcode='+referral_code+'&datefrom='+date_from+'&dateto='+date_to,
            dataType: 'html',
            success: function ( response ) {
              setTimeout(function(){
               $('#prog').hide();
               $('#monitorings').show();
               $('#monitorings').html(response);
                   }, 0);
                   
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });
    });
    $('#w2').on('change',function(e) {
        var referral_code=$('#w1').val();
        var date_from=$(this).val();
        var date_to=$('#w3').val();
        
        jQuery.ajax( {
            type: 'POST',
            url: '/referrals/referraltracking/search?rcode='+referral_code+'&datefrom='+date_from+'&dateto='+date_to,
            dataType: 'html',
            success: function ( response ) {
              setTimeout(function(){
               $('#prog').hide();
               $('#monitorings').show();
               $('#monitorings').html(response);
                   }, 0);
                   
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });
    });
    $('#w3').on('change',function(e) {
        var referral_code=$('#w1').val();
        var date_from=$('#w2').val();
        var date_to=$(this).val();
        jQuery.ajax( {
            type: 'POST',
            url: '/referrals/referraltracking/search?rcode='+referral_code+'&datefrom='+date_from+'&dateto='+date_to,
            dataType: 'html',
            success: function ( response ) {
              setTimeout(function(){
               $('#prog').hide();
               $('#monitorings').show();
               $('#monitorings').html(response);
                   }, 0);
                   
            },
            error: function ( xhr, ajaxOptions, thrownError ) {
                alert( thrownError );
            }
        });
    });
</script>    