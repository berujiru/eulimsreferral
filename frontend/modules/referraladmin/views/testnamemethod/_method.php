<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\referral\Sampletype;
use common\models\referral\Lab;
use common\models\referral\Testname;
use common\models\referral\Methodreference;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\DatePicker;
use kartik\datetime\DateTimePicker;

?>

<?= GridView::widget([
        'dataProvider' =>  $testnamedataprovider,
        'pjax' => true,    
        'id'=>'testname-grid',
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'containerOptions'=>[
            'style'=>'overflow:auto; height:380px',
        ],
        'columns' => [
            [
                'class' =>  '\kartik\grid\RadioColumn',
                'name' => 'method_id',
                'showClear' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center','style'=>'max-width:20px;'],
                'radioOptions' => function ($model) {
                    return [
                        'value' => $model['methodreference_id'],
                    ];
                },
            ],
            [     
                'label' => 'Method',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 30%;word-wrap: break-word;white-space:pre-line;'],  
                'value' => function($data) {
                    return $data->method;
                 }                        
            ],
            [     
                'label' => 'Reference',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 60%;word-wrap: break-word;white-space:pre-line;'],  
                'value' => function($data) {
                    return $data->reference;
                            
                 }                        
            ],
            [    
                'label' => 'Fee',
                'format' => 'raw',
                'width'=> '150px',
                'contentOptions' => ['style' => 'width: 10%;word-wrap: break-word;white-space:pre-line;'],  
                'value' => function($data) {
                    return $data->fee;
                 }                
            ],
       ],
    ]); ?>


<script type="text/javascript">
   $("#testname-grid").change(function(){
        var key = $("input[name='method_id']:checked").val();
        $("#testnamemethod-methodreference_id").val(key);
    });  
</script>