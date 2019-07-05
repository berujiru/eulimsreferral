<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Referral */

$this->title = empty($model->referral_code) ? $model->referral_id : $model->referral_code;
$this->params['breadcrumbs'][] = ['label' => 'Referrals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


if(count($notification) > 0 && $notification->notification_type_id == 1 && $notification->responded == 0 && empty(trim($model->referral_code))){
    $actionButtonConfirm = "<div class='row' style='margin-left: 2px;padding-top: 5px'>".Html::button('<span class="glyphicon glyphicon-ok"></span> Confirm Referral Notification', ['value'=>Url::to(['/referrals/referral/confirm','local_request_id'=>$model->local_request_id,'referral_id'=>$model->referral_id,'notice_id'=>$notification->notification_id,'sender_id'=>$notification->sender_id]),'onclick'=>'confirmNotification(this.value,this.title)','class' => 'btn btn-primary','title' => 'Confirm Referral of '.$model->agencyreceiving->name])."</div>";
} else {
    $actionButtonConfirm = "";
}

if(count($notification) > 0 && $notification->notification_type_id == 3 && $notification->responded == 0){
    $actionButtonGenerateSamplecode = "<div class='row' style='margin-left: 2px;padding-top: 5px'>".Html::button('<span class="glyphicon glyphicon-ok"></span> Confirm Referral Notification', ['value'=>Url::to(['/referrals/referral/confirm','local_request_id'=>$model->local_request_id,'referral_id'=>$model->referral_id,'notice_id'=>$notification->notification_id,'sender_id'=>$notification->sender_id]),'onclick'=>'confirmNotification(this.value,this.title)','class' => 'btn btn-primary','title' => 'Confirm Referral of '.$model->agencyreceiving->name])."</div>";
} else {
    $actionButtonGenerateSamplecode = "";
}

/*if(count($notification) > 0 && $notification->notification_type_id == 3 && $notification->responded == 0){
    $actionButtonSaveLocal= "<div class='row' style='margin-left: 2px;padding-top: 5px'>".Html::button('<span class="glyphicon glyphicon-save"></span> Save as Local Request', ['value'=>Url::to(['/referrals/referral/savelocal','referral_id'=>$model->referral_id,'notice_id'=>$notification->notification_id]),'onclick'=>'localSave(this.value,this.title)','class' => 'btn btn-primary','title' => 'Save as Local Request']);
} else {
    $actionButtonSaveLocal = "";
}*/
?>
<div class="referral-view">
    <div class="image-loader" style="display: none;"></div>
    <div class="container table-responsive">
        <?php
            echo DetailView::widget([
            'model'=>$model,
            'responsive'=>true,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=>'<i class="glyphicon glyphicon-book"></i> Referral Code ' . $model->referral_code,
                'type'=>DetailView::TYPE_PRIMARY,
            ],
            'buttons1' => '',
            'attributes'=>[
                [
                    'group'=>true,
                    'label'=>'Referral Details ',
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Referral Code',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%'],
                            'value'=> $model->referral_code,
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
                            'label'=>'Referral Date / Time',
                            'format'=>'raw',
                            'value'=> ($model->referral_date_time != "0000-00-00 00:00:00") ? Yii::$app->formatter->asDate($model->referral_date_time, 'php:F j, Y h:i a') : "<i class='text-danger font-weight-bold h5'>Pending referral request</i>",
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
                            'label'=>'Sample Received Date',
                            'format'=>'raw',
                            'value'=> !empty($model->sample_received_date) ? Yii::$app->formatter->asDate($model->sample_received_date, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No sample received date</i>",
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
                    'columns' => [
                        [
                            'label'=>'Estimated Due Date',
                            'format'=>'raw',
                            'value'=> ($model->report_due != "0000-00-00 00:00:00") ? Yii::$app->formatter->asDate($model->report_due, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>Pending referral request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Fax no.',
                            'format'=>'raw',
                            'value'=> $model->customer_id > 0 && !empty($model->customer->customer_name) ? $model->customer->fax : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Referred by',
                            'format'=>'raw',
                            'value'=> !empty($model->agencyreceiving) ? $model->agencyreceiving->name : null,
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Referred to',
                            'format'=>'raw',
                            'value'=> !empty($model->agencytesting) ? $model->agencytesting->name : null,
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'group'=>true,
                    'label'=>'Payment Details',
                    'rowOptions'=>['class'=>'info']
                ],
                [
                    'columns' => [
                        [
                            'label'=>'Deposite Slip',
                            'value'=>function($data) use ($depositslip,$model){
                                $link = '';
                                if($depositslip > 0){
                                    foreach ($depositslip as $deposit) {
                                        $link .= Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$deposit['filename'],'/referrals/attachment/download?request_id='.$model->local_request_id.'&file='.$deposit['attachment_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Deposit Slip','target'=>'_self'])."<br>";
                                    }
                                }
                                return $link;
                            },
                            'format'=>'raw',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%;vertical-align: top;'],
                            'labelColOptions' => ['style' => 'width: 20%; text-align: right; vertical-align: top;'],
                        ],
                        [
                            'label'=>'Official Receipt',
                            'format'=>'raw',
                            'value'=>function($data) use ($officialreceipt,$model){
                                $link = '';
                                if($officialreceipt > 0){
                                    foreach ($officialreceipt as $or) {
                                        $link .= Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$or['filename'],'/referrals/attachment/download?request_id='.$model->local_request_id.'&file='.$or['attachment_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Official Receipt','target'=>'_self'])."<br>";
                                    }
                                }
                                return $link;
                            },
                            'valueColOptions'=>['style'=>'width:30%;vertical-align: top;'], 
                            'displayOnly'=>true,
                            'labelColOptions' => ['style' => 'width: 20%; text-align: right; vertical-align: top;'],
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
                            'attribute' => 'cro_receiving',
                            'format'=>'raw',
                            //'value'=>$model->cro_receiving,
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%']
                        ],
                        [
                            'label'=>'Conforme',
                            'attribute' => 'conforme',
                            //'value'=> $model->conforme,
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
            $gridColumns = [
                [
                    'attribute'=>'sample_code',
                    'enableSorting' => false,
                    'contentOptions' => [
                        'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
                [
                    'attribute'=>'sample_name',
                    'enableSorting' => false,
                ],
                [
                    'attribute'=>'description',
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($data) use ($model){
                        return ($model->lab_id == 2) ? "Sampling Date: <span style='color:#000077;'><b>".date("Y-m-d h:i A",strtotime($data->sampling_date))."</b></span>,&nbsp;".$data->description : $data->description;
                    },
                   'contentOptions' => [
                        'style'=>'max-width:180px; overflow: auto; white-space: normal; word-wrap: break-word;'
                    ],
                ],
            ];

            echo GridView::widget([
                'id' => 'sample-grid',
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
                    'before'=>null,
                    'after'=>false,
                ],
                'columns' => $gridColumns,
                'toolbar' => [
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['referral/view','id'=>$model->referral_id])], [
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
                    'attribute'=>'sample.sample_name',
                    'header'=>'Sample',
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($data) {
                        return !empty($data->sample) ? $data->sample->sample_name : null;
                    },
                    'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                   
                ],
                [
                    'attribute'=>'sample.sample_code',
                    'header'=>'Sample Code',
                    'value' => function($data) {
                        return !empty($data->sample) ? $data->sample->sample_code : null;
                    },
                    'format' => 'raw',
                    'enableSorting' => false,
                    'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                ],
                [
                    'attribute'=>'testname.test_name',
                    'format' => 'raw',
                    'header'=>'Test/ Calibration Requested',
                    'contentOptions' => ['style' => 'width: 15%;word-wrap: break-word;white-space:pre-line;'],
                    'enableSorting' => false,
                ],
                [
                    'attribute'=>'methodreference.method',
                    'format' => 'raw',
                    'header'=>'Test Method',
                    'enableSorting' => false,  
                    'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
                    'pageSummary' => '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>',             
                ],
                [
                    'attribute'=>'analysis_fee',
                    'header'=>'Unit Price',
                    'enableSorting' => false,
                    'hAlign'=>'right',
                    'format' => 'raw',
                    'value'=>function($data){
                        return number_format($data['analysis_fee'],2);
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
            ];
            echo GridView::widget([
                'id' => 'analysis-grid',
                'responsive'=>true,
                'dataProvider'=> $analysisdataprovider,
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
                    'before'=> null,
                    'after'=> false,
                    'footer'=>$actionButtonConfirm.$actionButtonGenerateSamplecode,
                    //'footer'=>$actionButtonConfirm,
                ],
                'columns' => $analysisgridColumns,
                'toolbar' => [
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['referral/view','id'=>$model->referral_id])], [
                                'class' => 'btn btn-default', 
                                'title' => 'Refresh Grid'
                            ]),
                ],
            ]);
        ?>
    </div>
</div>
<script type="text/javascript">
    $('#sample-grid tbody td').css('cursor', 'pointer');
    //upload slip
    function upload(url,title){
        $('.modal-title').html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function confirmNotification(url,title){
        var str = title.slice(19);
        var header_title = '';

        if(title.length > 73){
            header_title = title.slice(0, 70) + '...';
        } else {
            header_title = title;
        }

        if(str.length > 0){
            var agency_name = str;
        } else {
            var agency_name = "<span style='font-size:10px;color:#757575;'>...No agency to be displayed...</span>";
        }

        BootstrapDialog.show({
            title: "<span class='glyphicon glyphicon-send'></span>&nbsp;&nbsp;" + header_title,
            message: "<p class='note' style='margin:15px 0 0 15px;font-weight:bold;color:#990000;font-size:14px;'><span class='glyphicon glyphicon-exclamation-sign' style='font-size:17px;'></span> Are you sure you want to confirm the referral notification of <span class='agency-name' style='color:#000000;'>"+agency_name+"</span>?</p>",
            buttons: [
                {
                    label: 'Proceed',
                    cssClass: 'btn-primary',
                    action: function(thisDialog){
                        thisDialog.close();
                        $('.modal-title').html(header_title);
                        $('#modal').modal('show')
                            .find('#modalContent')
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
</script>

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
    echo "<div class='modal-scroll'><div id='modalContent' style='margin-left: 5px;'><div style='text-align:center;'><img src='/images/img-loader64.gif' alt=''></div></div>";
    echo "<div>&nbsp;</div>";
    echo "</div></div>";
Modal::end();
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