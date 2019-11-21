<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;
//use yii\web\JsExpression;
//use yii\widgets\ListView;
//use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcrequest */

//$this->title = $model->pstc_request_id;
$this->title = empty($model->respond->request_ref_num) ? $model->pstc_request_id : $model->respond->request_ref_num;
$this->params['breadcrumbs'][] = ['label' => 'Pstcrequests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$reference_num = !empty($model->respond->request_ref_num) ? $model->respond->request_ref_num : '<i class="text-danger font-italic">Pending request</i>';

if(!empty($model->respond->request_ref_num)){//With Reference
    $disableButton="false";
}else{ // NO reference number yet
    $disableButton="true";
}

$accepted = $model->accepted;
$requestId = $model->pstc_request_id;
$for_referral_request = ($model->is_referral == 1) ? "<em style='color:#990000;font-size:12px;'>(For Referral Request) </em>" : "";
$btn_add_sample_received = empty($model->sample_received_date) ? Html::button('<i class="glyphicon glyphicon-plus"></i> Add Sample Received Date', ['value' => Url::to(['/pstc/pstcrequest/sample_received_date','request_id'=>$model->pstc_request_id]),'title'=>'Add Sample Received Date', 'onclick'=>'addSample(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']) : Html::button('<i class="glyphicon glyphicon-edit"></i> Update Sample Received Date', ['value' => Url::to(['/pstc/pstcrequest/sample_received_date','request_id'=>$model->pstc_request_id]),'title'=>'Update Sample Received Date', 'onclick'=>'addSample(this.value,this.title)', 'class' => 'btn btn-primary','id' => 'modalBtn']);
?>
<div class="pstcrequest-view">
    <div class="image-loader" style="display: none;"></div>
    <div class="container table-responsive">
        <?php
            echo DetailView::widget([
            'model'=>$model,
            'responsive'=>true,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=>'<i class="glyphicon glyphicon-book"></i> Request Reference No. : ' . $reference_num,
                'type'=>DetailView::TYPE_PRIMARY,
            ],
            'buttons1' => '',
            'attributes'=>[
                [
                    'group'=>true,
                    'label'=>'Request Details '.$for_referral_request,
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Customer Requested Date',
                            'format'=>'raw',
                            'value' => date('F j, Y h:i A', strtotime($model->created_at)),
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'PSTC',
                            'format'=>'raw',
                            'value'=> !empty($model->pstc->name) ? $model->pstc->name : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Request Reference Number',
                            'format' => 'raw',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%'],
                            'value'=> $reference_num,
                        ],
                        [
                            'label'=>'Customer / Agency',
                            'format'=>'raw',
                            'value'=> $model->customer_id > 0 && !empty($model->customer->customer_name) ? $model->customer->customer_name : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                    
                ],
                [
                    'columns' => [
                       [
                            'label'=>'Local Request Created Date',
                            'format'=>'raw',
                            'value'=> !empty($model->respond->request_date_created)  ? date('F j, Y h:i A', strtotime($model->respond->request_date_created)) : "<i class='text-danger font-italic'>Pending request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Address',
                            'format'=>'raw',
                            'value'=> $model->customer_id > 0 && !empty($model->customer->customer_name) ? $model->customer->address : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Estimated Due Date',
                            'format'=>'raw',
                            'value'=> !empty($model->respond->estimated_due_date)  ? date('F j, Y', strtotime($model->respond->estimated_due_date)) : "<i class='text-danger font-italic'>Pending request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Tel no.',
                            'format'=>'raw',
                            'value'=> $model->customer_id > 0 && !empty($model->customer->customer_name) ? $model->customer->tel : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],       
                [
                    'group'=>true,
                    'label'=>'Transaction Details',
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'columns' => [
                        [ 
                            'label'=>'Recieved By',
                            'attribute' => 'received_by',
                            'format'=>'raw',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%']
                        ],
                        [
                            'label'=>'Submitted By',
                            'attribute' => 'submitted_by',
                            'format'=>'raw',
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
            ],

        ]);
        ?>
    </div>
    <div class="container">
        <div class="table-responsive">
        <?php
            $sampleGridColumns = [
                [
                    'header' => 'Sample Code',
                    'attribute'=>'sample_code',
                    'enableSorting' => false,
                    'format' => 'raw',
                    'contentOptions' => [
                        'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'header' => 'Sample Name',
                    'attribute'=>'sample_name',
                    'enableSorting' => false,
                    'format' => 'raw',
                    'contentOptions' => [
                        'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'header' => 'Sample Description',
                    'attribute'=>'sample_description',
                    'format' => 'raw',
                    'enableSorting' => false,
                    //'value' => function($data) use ($model){
                    //    return ($model->lab_id == 2) ? "Sampling Date: <span style='color:#000077;'><b>".date("Y-m-d h:i A",strtotime($data->sampling_date))."</b></span>,&nbsp;".$data->description : $data->description;
                    //},
                   'contentOptions' => [
                        'style'=>'max-width:180px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update} {remove}',
                    'dropdown' => false,
                    'dropdownOptions' => ['class' => 'pull-right'],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'remove') {
                            $url ='/pstc/pstcsample/delete?id='.$model->pstc_sample_id;
                            return $url;
                        }
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            if($model->active == 1 && $model->request->accepted == 0){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '#', ['class'=>'btn btn-primary','title'=>'Update Sample','onclick' => 'updateSample('.$model->pstc_sample_id.','.$model->pstc_request_id.')']);
                            } else {
                                return null;
                            }
                        },
                        'remove' => function ($url, $model){
                            if($model->sample_code == "" && $model->active == 1 && $model->request->accepted == 0){
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,['data-confirm'=>"Are you sure you want to delete <b>".$model->sample_name."</b>?",'data-method'=>'post','class'=>'btn btn-danger','title'=>'Remove Sample','data-pjax'=>'0']);
                            } else {
                                return null;
                            }
                        },
                    ],
                ],
            ];

            echo GridView::widget([
                'id' => 'sample-grid',
                //'dataProvider'=> $sampleDataProvider,
                'dataProvider'=> $sampleDataProvider,
                'pjax'=>true,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
                'responsive'=>true,
                'striped'=>true,
                'hover'=>true,
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Samples</h3>',
                    'type'=>'primary',
                    //'before'=>null,
                    'after'=>false,
                    'before'=> ($accepted == 0) ? ($model->is_referral == 1 ? Html::button('<i class="glyphicon glyphicon-plus"></i> Add Sample', ['value' => Url::to(['/pstc/pstcsample/create','request_id'=>$model->pstc_request_id]),'title'=>'Add Sample', 'onclick'=>'addSample(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']).' '.$btn_add_sample_received : Html::button('<i class="glyphicon glyphicon-plus"></i> Add Sample', ['value' => Url::to(['/pstc/pstcsample/create','request_id'=>$model->pstc_request_id]),'title'=>'Add Sample', 'onclick'=>'addSample(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn'])) : '',
                ],
                'columns' => $sampleGridColumns,
                'toolbar' => [
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/pstc/pstcrequest/view','id'=>$model->pstc_request_id])], [
                                'class' => 'btn btn-default', 
                                'title' => 'Refresh Grid'
                            ]),
                ],
            ]);
        ?>
        </div>
    </div>
    <div class="container">
        <?php
            $analysisgridColumns = [
                [
                    //'attribute'=>'sample.sample_code',
                    'header'=>'Sample Code',
                    'value' => function($data) {
                        return !empty($data->sample) ? $data->sample->sample_code : null;
                    },
                    'format' => 'raw',
                    'enableSorting' => false,
                    'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                ],
                [
                    //'attribute'=>'sample.sample_name',
                    'header'=>'Sample Name',
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($data) {
                        return !empty($data->sample) ? $data->sample->sample_name : null;
                    },
                    'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                ],
                [
                    //'attribute'=>'testnames.testName',
                    'format' => 'raw',
                    'header'=>'Test/ Calibration Requested',
                    'contentOptions' => ['style' => 'width: 15%;word-wrap: break-word;white-space:pre-line;'],
                    'enableSorting' => false,
                    'value' => function($data) {
                       if($data->is_package_name == 1){
                            return $data->package_name;
                        } elseif($data->is_package_name == 0 && $data->is_package == 1){
                            return "&nbsp;&nbsp;<span style='font-size:12px;'>".$data->testname."</span>";
                        } else {
                            return $data->testname;
                        }
                    },
                    // 'value' => function($data) {
                    //     if($data->is_package_name == 1){
                    //         return $data->package_name;
                    //     } elseif($data->is_package_name == 0 && $data->is_package == 1){
                    //         return "&nbsp;&nbsp;<span style='font-size:12px;'>".(!empty($data->testnames) ? $data->testnames->testName : null)."</span>";
                    //     } else {
                    //         return !empty($data->testnames) ? $data->testnames->testName : null;
                    //     }
                    // },
                ],
                [
                    //'attribute'=>'methodrefs.method',
                    'format' => 'raw',
                    'header'=>'Test Method',
                    'enableSorting' => false,
                    // 'value' => function($model) {
                    //    return !empty($model->methodrefs) ? $model->methodrefs->method : null;
                    // },
                    'value' => function($data) {
                        if($data->is_package_name == 1){
                            return '-';
                        } elseif($data->is_package_name == 0 && $data->is_package == 1){
                            return "&nbsp;&nbsp;<span style='font-size:12px;'>".$data->method."</span>";
                        } else {
                            return $data->method;
                        }
                    },
                    'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
                    'pageSummary' => '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>',             
                ],
                [
                    'attribute'=>'fee',
                    'header'=>'Unit Price',
                    'enableSorting' => false,
                    'hAlign'=>'right',
                    'format' => 'raw',
                    // 'value'=>function($model){
                    //     return number_format($model->fee,2);
                    // },
                    'value'=>function($data){
                        return ($data->is_package_name == 0 & $data->is_package == 1) ? '-' : number_format($data->fee,2);
                    },
                    'contentOptions' => [
                        'style'=>'max-width:80px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                    'hAlign' => 'right', 
                    'vAlign' => 'left',
                    'width' => '7%',
                    'format' => 'raw',
                    'pageSummary'=> function () use ($subtotal,$discounted,$total,$countSample) {
                        if($countSample > 0){
                            return  '<div id="subtotal">₱'.number_format($subtotal, 2).'</div><div id="discount">₱'.number_format($discounted, 2).'</div><div id="total"><b>₱'.number_format($total, 2).'</b></div>';
                        } else {
                            return '';
                        }
                    },
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{update} {remove}',
                    'dropdown' => false,
                    'dropdownOptions' => ['class' => 'pull-right'],
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'buttons' => [
                        'update' => function ($url, $model) use ($accepted,$requestId) {
                            if($model->sample->sample_code == "" && $model->sample->active == 1 && $accepted == 0 && $model->is_package == 0 && $model->testcategory_id > 0) {
                                return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['onclick' => 'updateAnalysis('.$model->pstc_analysis_id.','.$model->sample->pstc_request_id.',this.title)', 'class' => 'btn btn-primary','title' => 'Update Analysis']);
                            } elseif($model->sample->sample_code == "" && $model->sample->active == 1 && $accepted == 0 && $model->is_package == 1 && $model->is_package_name == 1 && $model->testcategory_id > 0) {
                                return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/pstc/pstcanalysis/updatepackage','analysis_id'=>$model->pstc_analysis_id,'sample_id'=>$model->pstc_sample_id,'package_id'=>$model->package_id,'request_id'=>$requestId]),'onclick'=>'updatePackage(this.value,this.title)', 'class' => 'btn btn-primary','title' => 'Update Package']);
                            } elseif($model->sample->sample_code == "" && $model->sample->active == 1 && $accepted == 0 && $model->is_package == 0 && $model->testcategory_id == 0) {
                                return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['onclick' => 'updateReferralAnalysis('.$model->pstc_analysis_id.','.$model->sample->pstc_request_id.',this.title)', 'class' => 'btn btn-primary','title' => 'Update Analysis Not Offered']);
                            } else {
                                return null;
                            }
                        },
                        'remove' => function ($url, $model) use ($accepted,$requestId) {
                            if($model->is_package_name == 0 && $model->is_package == 0 && $accepted == 0) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '/pstc/pstcanalysis/delete?id='.$model->pstc_analysis_id.'&request_id='.$model->sample->pstc_request_id,['data-confirm'=>"Are you sure you want to delete this analysis?", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Remove Analysis','data-pjax'=>'0']);
                            } elseif($model->is_package_name == 1 && $model->is_package == 1 && $accepted == 0) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>','/pstc/pstcanalysis/deletepackage?sample_id='.$model->pstc_sample_id.'&package_id='.$model->package_id.'&request_id='.$requestId,['data-confirm'=>"This will delete all the analyses under this package. Click OK to proceed. <b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Remove Package','data-pjax'=>'0']);
                            } else {
                                return null;
                            }
                        },
                    ],
                ],
            ];

            $btn_add_analysis = $model->is_referral == 1 ? Html::button('<i class="glyphicon glyphicon-plus"></i> Add Analysis Not Offered', ['value' => Url::to(['/pstc/pstcanalysis/add_not_offer','request_id'=>$model->pstc_request_id]),'title'=>'Add Analysis Not Offered', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']) : Html::button('<i class="glyphicon glyphicon-plus"></i> Add Analysis', ['value' => Url::to(['/pstc/pstcanalysis/create','request_id'=>$model->pstc_request_id]),'title'=>'Add Analysis', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']).' '.Html::button('<i class="glyphicon glyphicon-plus"></i> Add Package', ['value' => Url::to(['/pstc/pstcanalysis/package','request_id'=>$model->pstc_request_id]),'title'=>'Add Package', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']);

            echo GridView::widget([
                'id' => 'analysis-grid',
                'responsive'=>true,
                'dataProvider'=> $analysisDataprovider,
                'pjax'=>true,
                'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ]
                ],
                'responsive'=>true,
                'striped'=>true,
                'hover'=>true,
                'showPageSummary' => true,
                'hover'=>true,
                
                'panel' => [
                    'heading'=>'<h3 class="panel-title">Analysis</h3>',
                    'type'=>'primary',
                    //'before'=> ($accepted == 0) ? ($model->is_referral == 1 ? Html::button('<i class="glyphicon glyphicon-plus"></i> Add Analysis Not Offered', ['value' => Url::to(['/pstc/pstcanalysis/add_not_offer','request_id'=>$model->pstc_request_id]),'title'=>'Add Analysis Not Offered', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']) : Html::button('<i class="glyphicon glyphicon-plus"></i> Add Analysis', ['value' => Url::to(['/pstc/pstcanalysis/create','request_id'=>$model->pstc_request_id]),'title'=>'Add Analysis', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']).' '.Html::button('<i class="glyphicon glyphicon-plus"></i> Add Package', ['value' => Url::to(['/pstc/pstcanalysis/package','request_id'=>$model->pstc_request_id]),'title'=>'Add Package', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn']).' '.Html::button('<i class="glyphicon glyphicon-plus"></i> Add Analysis Not Offered', ['value' => Url::to(['/pstc/pstcanalysis/add_not_offer','request_id'=>$model->pstc_request_id]),'title'=>'Add Analysis Not Offered', 'onclick'=>'addAnalysis(this.value,this.title)', 'class' => 'btn btn-success','id' => 'modalBtn'])) : '',

                    'before'=> ($accepted == 0 && $countSample > 0) ? $btn_add_analysis : '',

                    'after'=> $model->local_request_id > 0 && $accepted == 1 ? '' : ($model->is_referral == 1 ? Html::button('<i class="glyphicon glyphicon-repeat"></i> Revert for Local Request', ['value' => Url::to(['/pstc/pstcrequest/revertlocal','request_id'=>$model->pstc_request_id]),'title'=>'Revert for Local Request', 'onclick'=>'markReferral(this.value,this.title)', 'class' => 'btn btn-danger','id' => 'modalBtn']) : Html::button('<i class="glyphicon glyphicon-bookmark"></i> Mark for Referral Request', ['value' => Url::to(['/pstc/pstcrequest/markreferral','request_id'=>$model->pstc_request_id]),'title'=>'Mark for Referral Request', 'onclick'=>'markReferral(this.value,this.title)', 'class' => 'btn btn-primary','id' => 'modalBtn'])),
                    //'footer'=>$actionButtonConfirm.$actionButtonSaveLocal,
                    'footer'=>false,
                ],
                'columns' => $analysisgridColumns,
                'toolbar' => [
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/pstc/pstcrequest/view','id'=>$model->pstc_request_id])], [
                                'class' => 'btn btn-default', 
                                'title' => 'Refresh Grid'
                            ]),
                ],
            ]);
        ?>
    </div>
</div>
<?php
    Modal::begin([
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
        'bodyOptions'=>[
            'class' => 'modal-body',
            'style'=>'padding-bottom: 20px',
        ],
        'options' => [
            'id' => 'modalAnalysis',
            'tabindex' => false, // important for Select2 to work properly
            //'tabindex' => 0, // important for Select2 to work properly
        ],
        'header' => '<h4 class="fa fa-clone" style="padding-top: 0px;margin-top: 0px;padding-bottom:0px;margin-bottom: 0px"> <span class="modal-title" style="font-size: 16px;font-family: \'Source Sans Pro\',sans-serif;"></span></h4>',
        'size' => Modal::SIZE_LARGE,
    ]);
    echo "<div>";
    echo "<div class='modal-scroll'><div id='modalContent' style='margin-left: 5px;'><div style='text-align:center;'><img src='/images/img-png-loader64.png' alt=''></div></div>";
    echo "<div>&nbsp;</div>";
    echo "</div></div>";
    Modal::end();
?>

<?php
    Modal::begin([
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
        'bodyOptions'=>[
            'class' => 'modal-body',
            'style'=>'padding-bottom: 20px',
        ],
        'options' => [
            'id' => 'modalMark',
            'tabindex' => false, // important for Select2 to work properly
            //'tabindex' => 0, // important for Select2 to work properly
        ],
        'header' => '<h4 class="fa fa-clone" style="padding-top: 0px;margin-top: 0px;padding-bottom:0px;margin-bottom: 0px"> <span class="modal-title" style="font-size: 16px;font-family: \'Source Sans Pro\',sans-serif;"></span></h4>',
        'size' => Modal::SIZE_SMALL,
    ]);
    echo "<div>";
    echo "<div class='modal-scroll'><div id='modalBody' style='margin-left: 5px;'><div style='text-align:center;'><img src='/images/img-png-loader64.png' alt=''></div></div>";
    echo "<div>&nbsp;</div>";
    echo "</div></div>";
    Modal::end();
?>

<script type="text/javascript">
    function addSample(url,title){
        var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
        $('#modalContent').html(_replace);
        $(".modal-title").html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function addReceived_date(url,title) {
        var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
        $(".modal-title").html(title);
        $('#modalBody').html(_replace);
        $('#modalMark').modal('show')
            .find('#modalBody')
            .load(url);
    }

    function updateSample(id,requestId){
        var url = '/pstc/pstcsample/update?id='+id+'&request_id='+requestId;
        var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
        $('#modalContent').html(_replace);
        $('.modal-title').html('Update Sample');
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function markReferral(url,title){
        BootstrapDialog.show({
            title: "<span class='glyphicon glyphicon-warning-sign'></span>&nbsp;&nbsp; WARNING",
            message: "<p class='note' style='margin:15px 0 0 15px;font-weight:bold;color:#880000;font-size:14px;'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:17px;'></span> Continuing this action will delete all analyses. Please click proceed button to confirm. </p>",
            type: BootstrapDialog.TYPE_DANGER,
            buttons: [
                {
                    label: 'Proceed',
                    cssClass: 'btn-danger',
                    action: function(thisDialog){
                        thisDialog.close();
                        var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
                        $('.modal-title').html(title);
                        $('#modalBody').html(_replace);
                        $('#modalMark').modal('show')
                            .find('#modalBody')
                            .load(url);
                    }
                }, 
                {
                    label: 'Close',
                    action: function(thisDialog){
                        thisDialog.close();
                    }
                }
            ]
        });
    }

    function addAnalysis(url,title){
        var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
        $('#modalContent').html(_replace);
        $(".modal-title").html(title);
        $('#modalAnalysis').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function updateAnalysis(id,requestId,title){
        $.ajax({
            url: '/pstc/pstcanalysis/getdefaultpage?analysis_id='+id,
            success: function (data) {
                $('.image-loader').removeClass('img-loader');
                var url = '/pstc/pstcanalysis/update?id='+id+'&request_id='+requestId+'&page='+data;
                var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
                $('#modalContent').html(_replace);
                $('.modal-title').html(title);
                $('#modalAnalysis').modal('show')
                    .find('#modalContent')
                    .load(url);
            },
            beforeSend: function (xhr) {
                $('.image-loader').addClass('img-loader');
            }
        });
    }

    function updateReferralAnalysis(id,requestId,title){
        $.ajax({
            url: '/pstc/pstcanalysis/getreferraldefaultpage?analysis_id='+id,
            success: function (data) {
                $('.image-loader').removeClass('img-loader');
                var url = '/pstc/pstcanalysis/update_not_offer?id='+id+'&request_id='+requestId+'&page='+data;
                var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
                $('#modalContent').html(_replace);
                $('.modal-title').html(title);
                $('#modalAnalysis').modal('show')
                    .find('#modalContent')
                    .load(url);
            },
            beforeSend: function (xhr) {
                $('.image-loader').addClass('img-loader');
            }
        });
    }

    function updatePackage(url,title){
        var _replace = "<div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div>";
        $('#modalContent').html(_replace);
        $('.modal-title').html(title);
        $('#modalAnalysis').modal('show')
            .find('#modalContent')
            .load(url);
    }
</script>
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