<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use yii\helpers\Json;
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcanalysis */
/* @var $form yii\widgets\ActiveForm */


/* if(count($sampletype) > 0){
    $dataSampletype = $sampletype;
} else {
    $dataSampletype = []; //['' => 'No Sample type'] + $sampletype;
} */

if(count($testname) > 0){
    $dataTestname = $testname;
} else {
    $dataTestname = []; //['' => 'No Test name'] + $testname;
}

?>

<div class="pstcanalysis-form">
    <div class="image-loader" style="display: hidden;"></div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
            <?php
                if(Yii::$app->controller->action->id === 'update_not_offer'){
                    $checkSample = !empty($model->pstc_sample_id) ? $model->pstc_sample_id : null;

                    $gridColumns = [
                        [
                            'class' => '\kartik\grid\SerialColumn',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center','style'=>'max-width:20px;'],
                        ],
                        [
                            'class' =>  '\kartik\grid\RadioColumn',
                            'radioOptions' => function ($model) use ($checkSample) {
                                return [
                                    'value' => $model['pstc_sample_id'],
                                    'checked' => $model['pstc_sample_id'] == $checkSample,
                                ];
                            },
                            'name' => 'sample_id',
                            'showClear' => true,
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center','style'=>'max-width:20px;'],
                        ],
                        [
                            'attribute'=>'sample_name',
                            'enableSorting' => false,
                            'contentOptions' => ['style'=>'max-width:200px;'],
                        ],
                        [
                            'attribute'=>'sample_description',
                            'format' => 'raw',
                            'enableSorting' => false,
                            'contentOptions' => [
                                'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
                            ],
                        ],
                    ];

                    echo GridView::widget([
                        'id' => 'sample-analysis-grid',
                        'dataProvider'=> $sampleDataProvider,
                        'pjax'=>true,
                        'pjaxSettings' => [
                            'options' => [
                                'enablePushState' => false,
                            ]
                        ],
                        'containerOptions'=>[
                            'style'=>'overflow:auto; height:200px',
                        ],
                        'floatHeaderOptions' => ['scrollingTop' => true],
                        'responsive'=>true,
                        'striped'=>true,
                        'hover'=>true,
                        'bordered' => true,
                        'panel' => [
                           'heading'=>'<h3 class="panel-title">Samples</h3>',
                           'type'=>'primary',
                           'before' => '',
                           'after'=>false,
                           'footer'=>false,
                        ],
                        'columns' => $gridColumns,
                        'toolbar' => false,
                    ]);
                } else {
                    $sampleGridColumns = [
                        [
                            'class' => '\kartik\grid\SerialColumn',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center','style'=>'max-width:10px;'],
                        ],
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            //'class' => '\yii\grid\CheckboxColumn',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center','style'=>'max-width:10px;'],
                            'name' => 'sample_ids',
                            //'checked' => $model['sample_id'] == 125,
                            /*'checkboxOptions' => function($model, $key, $index, $column) use ($checkSample) {
                                //$bool = in_array($model->id_machine, $machines); 
                                return ['checked' => $model['sample_id'] == $check];
                            },*/
                            //'multiple' => false,
                        ],
                        [
                            'attribute'=>'sample_name',
                            'enableSorting' => false,
                            'contentOptions' => ['style'=>'max-width:200px;'],
                        ],
                        [
                            'attribute'=>'sample_description',
                            'format' => 'raw',
                            'enableSorting' => false,
                            /*'value' => function($data){
                                return ($data->request->lab_id == 2) ? "Sampling Date: <span style='color:#000077;'><b>".date("Y-m-d h:i A",strtotime($data->sampling_date))."</b></span>,&nbsp;".$data->description : $data->description;
                            },*/
                           'contentOptions' => [
                                'style'=>'max-width:200px; overflow: auto; white-space: normal; word-wrap: break-word;'
                            ],
                        ],
                    ];

                    echo GridView::widget([
                        'id' => 'sample-analysis-grid',
                        'dataProvider'=> $sampleDataProvider,
                        'pjax'=>true,                
                        'pjaxSettings' => [
                            'options' => [
                                'enablePushState' => false,
                            ]
                        ],
                        'containerOptions'=>[
                            'style'=>'overflow:auto; height:200px',
                        ],
                        'floatHeaderOptions' => ['scrollingTop' => true],
                        'responsive'=>true,
                        'striped'=>true,
                        'hover'=>true,
                        'bordered' => true,
                        'panel' => [
                           'heading'=>'<h3 class="panel-title">Samples</h3>',
                           'type'=>'primary',
                           'before' => '',
                           'after'=>false,
                           'footer'=>false,
                        ],
                        'columns' => $sampleGridColumns,
                        'toolbar' => false,
                    ]);
                }
            ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8">
        <?php

        /*  $testcategoryOptions = [
                'language' => 'en-US',
                'width' => '100%',
                'theme' => Select2::THEME_KRAJEE,
                'placeholder' => 'Select test category',
                'allowClear' => true,
            ]; */

            $sampletypeOptions = [
                'language' => 'en-US',
                'width' => '100%',
                'theme' => Select2::THEME_KRAJEE,
                'placeholder' => 'Select sample type',
                'allowClear' => true,
            ];

            $testnameOptions = [
                'language' => 'en-US',
                'width' => '100%',
                'theme' => Select2::THEME_KRAJEE,
                'placeholder' => 'Select test name',
                'allowClear' => true,
            ];

            /*echo $form->field($model,'testcategory_id')->widget(Select2::classname(),[
                'data' => $testcategory,
                'theme' => Select2::THEME_KRAJEE,
                //'theme' => Select2::THEME_BOOTSTRAP,
                'options' => $testcategoryOptions,
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    "change" => "function() {
                        var testcategoryId = this.value;
                        var analysisId = '".$model->pstc_analysis_id."';
                        var select = $('#pstcanalysis-sampletype_id');
                        select.find('option').remove().end();
                        if (testcategoryId > 0){
                            $.ajax({
                                url: '".Url::toRoute("pstcanalysis/get_sampletype")."',
                                method: 'GET',
                                data: {testcategory_id:testcategoryId,analysis_id:analysisId},
                                success: function (data) {
                                    var select2options = ".Json::encode($sampletypeOptions).";
                                    select2options.data = data.data;
                                    select.select2(select2options);
                                    select.val(data.selected).trigger('change');
                                    $('.image-loader').removeClass('img-loader');
                                },
                                beforeSend: function (xhr) {
                                    $('.image-loader').addClass('img-loader');
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>Error Encountered!</p>\");
                                }
                            });
                        } else {
                            //alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No test category selected!</p>\");
                            console.log('No test category selected!');
                            select.val('').trigger('change');
                        }
                    }",
                ],
            ])->label('Test Category'); */
        ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8 required">
        <?php

            echo $form->field($model,'sampletype_id')->widget(Select2::classname(),[
                //'data' => $testcategory,
                'data' => $sampletype,
                'theme' => Select2::THEME_KRAJEE,
                //'theme' => Select2::THEME_BOOTSTRAP,
                'options' => $sampletypeOptions,
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    "change" => "function() {
                        var sampletypeId = this.value;
                        var analysisId = '".$model->pstc_analysis_id."';
                        var select = $('#pstcanalysis-testname_id');
                        select.find('option').remove().end();
                        if (sampletypeId > 0){
                            $.ajax({
                                url: '".Url::toRoute("pstcanalysis/get_referral_testname")."',
                                method: 'GET',
                                data: {sampletype_id:sampletypeId,analysis_id:analysisId},
                                success: function (data) {
                                    var select2options = ".Json::encode($testnameOptions).";
                                    select2options.data = data.data;
                                    select.select2(select2options);
                                    select.val(data.selected).trigger('change');
                                    $('.image-loader').removeClass('img-loader');
                                },
                                beforeSend: function (xhr) {
                                    $('.image-loader').addClass('img-loader');
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>Error Encountered!</p>\");
                                }
                            });
                        } else {
                            //alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No sample type selected!</p>\");
                            console.log('No sample type selected!');
                            select.val('').trigger('change');
                        }
                    }",
                ],
            ])->label('Sample Type');
        ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8 required">
        <?php

            echo $form->field($model,'testname_id')->widget(Select2::classname(),[
                'data' => $dataTestname,
                'theme' => Select2::THEME_KRAJEE,
                //'theme' => Select2::THEME_BOOTSTRAP,
                'options' => $testnameOptions,
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    "change" => "function() {
                        var testnameId = this.value;
                        var analysisId = '".$model->pstc_analysis_id."';
                        $.ajax({
                            url: '".Url::toRoute("pstcanalysis/get_referral_testnamemethod")."',
                            //dataType: 'json',
                            method: 'GET',
                            data: {testname_id:testnameId,analysis_id:analysisId},
                            //data: $(this).serialize(),
                            success: function (data, textStatus, jqXHR) {
                                $('.image-loader').removeClass( \"img-loader\" );
                                $('#referralmethodreference').html(data);
                            },
                            beforeSend: function (xhr) {
                                //alert('Please wait...');
                                $('.image-loader').addClass( \"img-loader\" );
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>Error Encountered!</p>\");
                            }
                        });
                    }",
                ],
            ])->label('Test Name');
        ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="referralmethodreference">
                <?php
                    echo $this->render('_referralmethodreference', [ 'methodProvider' => $methodrefDataProvider,'model'=>$model]);
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-group" style="padding-bottom: 3px;">
        <div style="float:right;">
            <?= Html::button($model->isNewRecord ? 'Add' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'add_analysis']) ?>
            <?= Html::button('Close', ['class' => 'btn', 'onclick'=>'closeDialog()']) ?>
            <br>
        </div>
    </div>
    </div>

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
    function closeDialog(){
        $(".modal").modal('hide'); 
    };
</script>

<?php

    if(Yii::$app->controller->action->id === 'update_not_offer'){
        $this->registerJs("
            $('#add_analysis').on('click',function(){
                //var radioSample = $('#sample-analysis-grid').yiiGridView('getSelectedRows');
                var radioSample = $(\"input[name='sample_id']:checked\").val();
                var radioMethod = $(\"input[name='method_id']:checked\").val();
                //var testcategory = $('#pstcanalysis-testcategory_id').val();
                var sampletype = $('#pstcanalysis-sampletype_id').val();
                var testname = $('#pstcanalysis-testname_id').val();
                
                if (!$(\"input[name='sample_id']\").is(':checked') || radioSample == '') {
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No sample selected!</p>\");
                    return false;
                }
                /*else if (!testcategory){
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No test category selected!</p>\");
                    return false;
                } */
                else if (!sampletype){
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No sample type selected!</p>\");
                    return false;
                }
                else if (!testname){
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No test name selected!</p>\");
                    return false;
                }
                else if ($('input[type=radio][name=method_id]', '#method-referral-reference-grid').length < 1) {
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No Method selected!</p>\");
                    return false;
                }
                else if (!$(\"input[name='method_id']\").is(':checked') || radioMethod == '') {
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No Method selected!</p>\");
                    return false;
                }
                else {
                    $('.image-loader').addClass('img-loader');
                    $('.pstcanalysis-form form').submit();
                    $('.image-loader').addClass('img-loader');
                }
            });
        ");
    } else {
        $this->registerJs("
            $('#add_analysis').on('click',function(){
                var key_sample = $('#sample-analysis-grid').yiiGridView('getSelectedRows');
                var radioMethod = $(\"input[name='method_id']:checked\").val();
                //var testcategory = $('#pstcanalysis-testcategory_id').val();
                var sampletype = $('#pstcanalysis-sampletype_id').val();
                var testname = $('#pstcanalysis-testname_id').val();
                
                if(key_sample.length < 1) {
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No sample selected!</p>\");
                    return false;
                }
                /*else if (!testcategory){
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No test category selected!</p>\");
                    return false;
                } */
                else if (!sampletype){
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No sample type selected!</p>\");
                    return false;
                }
                else if (!testname){
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No test name selected!</p>\");
                    return false;
                }
                else if ($('input[type=radio][name=method_id]', '#method-referral-reference-grid').length < 1) {
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No Method selected!</p>\");
                    return false;
                }
                else if(!$(\"input[name='method_id']\").is(':checked') || radioMethod == '') {
                    alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No Method selected!</p>\");
                    return false;
                }
                else {
                    $('.image-loader').addClass('img-loader');
                    $('.pstcanalysis-form form').submit();
                    $('.image-loader').removeClass('img-loader');
                }
            });
        ");
    }
?>
<style type="text/css">
/* Absolute Center Spinner */
.img-loader {
    position: fixed;
    z-index: 999;
    /*height: 2em;
    width: 2em;*/
    height: 64px;
    width: 64px;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-image: url('/images/img-png-loader64.png');
    background-repeat: no-repeat;
}
/* Transparent Overlay */
.img-loader:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.3);
}
</style>