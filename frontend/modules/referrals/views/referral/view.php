<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;
use yii\web\JsExpression;
use yii\widgets\ListView;
use kartik\tabs\TabsX;
/* @var $this yii\web\View */
/* @var $model common\models\referral\Referral */

$this->title = empty($model->referral_code) ? $model->referral_id : $model->referral_code;
$this->params['breadcrumbs'][] = ['label' => 'Referrals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile("/css/modcss/progress.css", [], 'css-search-bar');

$rstl_id=$rstl_id=(int) Yii::$app->user->identity->profile->rstl_id;
$statusreceived="progress-todo";
$statusshipped="progress-todo";
$statusaccepted="progress-todo";
$statusongoing="progress-todo";
$statuscompleted="progress-todo";
$statusuploaded="progress-todo";

foreach($logs as $log){
    if($log->referralstatus_id == 1){
       $statusreceived ="progress-done";
    }
    if($log->referralstatus_id == 2){
       $statusshipped ="progress-done";
    }
    if($log->referralstatus_id == 3){
       $statusaccepted ="progress-done";
    }
    if($log->referralstatus_id == 4){
       $statusongoing ="progress-done";
    }
    
    if($log->referralstatus_id == 5){
       $statuscompleted ="progress-done";
    }
    if($log->referralstatus_id == 6){
       $statusuploaded ="progress-done";
    }
}

$haveStatus = "";
if(!isset(\Yii::$app->session['config-item'])){
   \Yii::$app->session['config-item']=1; //Laboratories 
}
switch(\Yii::$app->session['config-item']){
    case 1: 
        $LogActive=true;
        $ResultActive=false;
        $TrackActive=false;
        break;
    case 2: 
        $LogActive=false;
        $ResultActive=true;
        $TrackActive=false;
        break;
    case 3: 
        $LogActive=false;
        $ResultActive=false;
        $TrackActive=true;
        break;
}
$Session= Yii::$app->session;

if(empty($model->referral_code)){
    $labelpanel = '<i class="glyphicon glyphicon-book"></i> Referral Code ' . $model->referral_code;
} else {
    //$btnPrint = "<a href='/referrals/referral/print-referral?id=".$model->referral_id."' class='btn-sm btn-default' style='color:#000000;margin-left:15px;'><i class='fa fa-print'></i> Print</a>";
    $btnPrint = "<a href='/referrals/referral/printref?id=".$model->referral_id."' class='btn btn-success' style='margin-left: 5px' target='_blank'><i class='fa fa-print'></i> Print Referral</a>";
    $labelpanel = '<i class="glyphicon glyphicon-book"></i> Referral Code ' . $model->referral_code .' '.$btnPrint;
}

$rstlId = Yii::$app->user->identity->profile->rstl_id;

if($countNotificationtype3 > 0) {
    $actionButtonGenerateSamplecode = Html::button('<span class="glyphicon glyphicon-qrcode"></span> Generate Sample Code', ['value'=>Url::to(['/referrals/referral/generatesamplecode','referral_id'=>$model->referral_id,'notice_id'=>$notificationtype3->notification_id]),'onclick'=>'generateSampleCode(this.value,this.title)','class' => 'btn btn-primary','title' => 'Generate Sample Code']);
} else {
    $actionButtonGenerateSamplecode = "";
}

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
                'heading'=>$labelpanel,
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
                            'value'=>function($data) use ($depositslip,$model,$rstlId){
                                $link = '';
                                $link .= !empty($model->referral_code) && $model->receiving_agency_id  == $rstlId ? Html::button('<span class="glyphicon glyphicon-upload"></span> Upload', ['value'=>Url::to(['/referrals/attachment/upload_deposit','referral_id'=>$model->referral_id]), 'onclick'=>'upload(this.value,this.title)', 'class' => 'btn btn-primary btn-xs','title' => 'Upload Deposit Slip'])."<br>" : '';
                                if($depositslip > 0){
                                    foreach ($depositslip as $deposit) {
                                        $link .= Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$deposit['filename'],'/referrals/attachment/download?referral_id='.$model->referral_id.'&file='.$deposit['attachment_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Deposit Slip','target'=>'_self'])."<br>";
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
                            'value'=>function($data) use ($officialreceipt,$model,$rstlId){
                                $link = '';
                                $link .= !empty($model->referral_code) && $model->testing_agency_id == $rstlId ? Html::button('<span class="glyphicon glyphicon-upload"></span> Upload', ['value'=>Url::to(['/referrals/attachment/upload_or','referral_id'=>$model->referral_id]), 'onclick'=>'upload(this.value,this.title)', 'class' => 'btn btn-primary btn-xs','title' => 'Upload Official Receipt'])."<br>" : '';
                                if($officialreceipt > 0){
                                    foreach ($officialreceipt as $or) {
                                        $link .= Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$or['filename'],'/referrals/attachment/download?referral_id='.$model->referral_id.'&file='.$or['attachment_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Official Receipt','target'=>'_self'])."<br>";
                                    }
                                }
                                return $link;
                            },
                            'format'=>'raw',
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%;vertical-align: top;'],
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
    <?php 
    //echo Html::button('<span class="glyphicon glyphicon-plus"></span> Place Bid', ['value'=>Url::to(['/referrals/bid/placebid','referral_id'=>$model->referral_id]), 'onclick'=>'sendNotification(this.value,this.title)', 'class' => 'btn btn-primary btn-xs','title' => 'Place Bid'])."<br>";

    //echo Html::button('<span class="glyphicon glyphicon-plus"></span> Place Bid', ['value'=>Url::to(['/referrals/bid/placebid','referral_id'=>$model->referral_id]), 'onclick'=>'placebid(this.value,this.title)', 'class' => 'btn btn-primary btn-xs','title' => 'Place Bid'])."<br>"; 

    ?>
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
                [
                    'attribute'=>'customer_description',
                    'header'=>'Description provided by Customer',
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($data){
                        return empty($data->customer_description) ? "<span style='color:#444444;font-size:11px;'><i>No information provided</i></span>" : $data->customer_description;
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
                    'before'=>$actionButtonGenerateSamplecode,
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
                    'footer'=>null,
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
    <?php if($model->referral_code){ ?>
    <div class="container" <?php echo $haveStatus; ?>>
        <ul class="progress-track">
                <li class="<?php echo $statusreceived; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-dropbox fa-fw to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Received</span>
                </li>
                <li class="<?php echo $statusshipped; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-truck fa-fw fa-flip-horizontal to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Shipped</span>
                </li>
                <li class="<?php echo $statusaccepted; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span style="margin-left:2px;" class="fa fa-cube fa-fw fa-lg to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Accepted</span>
                </li>
                <li class="<?php echo $statusongoing; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-flask fa-fw to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Ongoing</span>
                </li>
                <li class="<?php echo $statuscompleted; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-check fa-fw to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Completed</span>
                </li>
                <li class="<?php echo $statusuploaded; ?> progress-tooltip">
                        <div class="progress-icon-wrap">
                                <span class="fa fa-upload fa-fw to-fit-icon" aria-hidden="true"></span>
                        </div>
                        <span class="progress-tooltiptext">Uploaded</span>
                </li>
        </ul>
        <?php
       // echo "<a href='/referrals/referral/printref?id=".$model->referral_id."' class='btn btn-success' style='margin-left: 5px' target='_blank'><i class='fa fa-print'></i> Print Referral</a>";
       // echo "<br /><br />";
        ?>
    </div>
    
      <div class="container">
         <div class="panel panel-primary">
        
        <div class="panel-body">
        <?php  
        
         $trackreceiving=DetailView::widget([
                'model' =>$modelRefTrackreceiving,
                'responsive'=>true, 
             
                'hover'=>true,
                'mode'=>DetailView::MODE_VIEW,
                'panel'=>[
                    'type'=>DetailView::TYPE_PRIMARY,
                ],
                'buttons1' => '',
                'attributes' => [
                    [
                       
                        'columns' => [
                            [
                                'label'=>'Referred to',
                                'format'=>'raw',
                                'value'=>!empty($model->agencytesting) ? $model->agencytesting->name : null,
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                            [
                                'label'=>'Referral Code',
                                'format'=>'raw',
                                'value'=>$model->referral_code,
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                        ],

                    ], 
                    [
                        'columns' => [
                            [
                                'label'=>'Date Received from Customer',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTrackreceiving->sample_received_date) ? Yii::$app->formatter->asDate($modelRefTrackreceiving->sample_received_date, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No sample received date</i>",//$model->transactionnum,
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                            [
                                'label'=>'Courier',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTrackreceiving->courier) ? $modelRefTrackreceiving->courier->name : "<i class='text-danger font-weight-bold h5'>No courier</i>",//$model->customer ? $model->customer->customer_name : "",
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                        ],

                    ], 
                     [
                        'columns' => [
                            [
                                'label'=>'Shipping Date',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTrackreceiving->shipping_date) ? Yii::$app->formatter->asDate($modelRefTrackreceiving->shipping_date, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No data</i>",
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                            [
                                'label'=>'Calibration Specimen Received from Customer',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTrackreceiving->cal_specimen_received_date) ? Yii::$app->formatter->asDate($modelRefTrackreceiving->cal_specimen_received_date, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No data</i>",
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                        ],

                    ],
                ],
            ]);
         $tracktesting=DetailView::widget([
                'model' =>$modelRefTracktesting,
                'responsive'=>true, 
             
                'hover'=>true,
                'mode'=>DetailView::MODE_VIEW,
                'panel'=>[
                    'type'=>DetailView::TYPE_PRIMARY,
                ],
                'buttons1' => '',
                'attributes' => [
                  
                    [
                        'columns' => [
                            [
                                'label'=>'Referred by',
                                'format'=>'raw',
                                'value'=>!empty($model->agencyreceiving) ? $model->agencyreceiving->name : null,//$model->transactionnum,
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                            [
                                'label'=>'Referral Code',
                                'format'=>'raw',
                                'value'=>$model->referral_code,
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                        ],

                    ], 
                    [
                        'columns' => [
                            [
                                'label'=>'Date Received from Courier',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTracktesting->date_received_courier) ? Yii::$app->formatter->asDate($modelRefTracktesting->date_received_courier, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No received date</i>",
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                            [
                                'label'=>'Analysis/Calibration Started',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTracktesting->analysis_started) ? Yii::$app->formatter->asDate($modelRefTracktesting->analysis_started, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No data</i>",
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                        ],

                    ], 
                     [
                        'columns' => [
                            [
                                'label'=>'Analysis/Calibration Completed',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTracktesting->analysis_completed) ? Yii::$app->formatter->asDate($modelRefTracktesting->analysis_completed, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No data</i>",
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                            [
                                'label'=>'Courier',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTracktesting->courier) ? $modelRefTracktesting->courier->name : "<i class='text-danger font-weight-bold h5'>No courier</i>",
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                        ],

                    ],
                    [
                        'columns' => [
                            [
                                'label'=>'Calibration Specimen Send back to Receiving Lab',
                                'format'=>'raw',
                                'value'=>!empty($modelRefTracktesting->cal_specimen_send_date) ? Yii::$app->formatter->asDate($modelRefTracktesting->cal_specimen_send_date, 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No data</i>",
                                'valueColOptions'=>['style'=>'width:30%'], 
                                'displayOnly'=>true
                            ],
                            [
                                'label'=>'',
                                'format'=>'raw',
                                'value'=>''
                            ],
                        ],

                    ],
                ],
            ]);
        
      
        $gridColumnsResults="<div class='row'><div class='col-md-12'>". GridView::widget([
            'dataProvider' => $testresult,
            'id'=>'Grid',
            'tableOptions'=>['class'=>'table table-hover table-stripe table-hand'],
            'pjax'=>true,
            'pjaxSettings' => [
                    'options' => [
                        'enablePushState' => false,
                    ],
            ],
            'toolbar'=>[],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<i class="fa fa-columns"></i> List',
             ],
            'columns' => [
                [
                    'label' => 'Result',
                    'format'=>'raw',
                    'value' =>  function($testresult){
                        return Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$testresult['filename'],'/referrals/attachment/download?referral_id='.$testresult['referral_id'].'&file='.$testresult['attachment_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Result','target'=>'_self'])."<br>";
                    }
                ],
                
            ],
        ])."</div></div>";
                
        if($model->receiving_agency_id == $rstl_id){
            $Func="LoadModal('Update Tracking','/referrals/referraltrackreceiving/update?id=".$modelRefTrackreceiving->referraltrackreceiving_id."',true,500)";
            $UpdateButton='<button id="btnUpdate" onclick="'.$Func.'" type="button" style="float: left;padding-right:5px;margin-left: 5px" class="btn btn-primary"><i class="fa fa-pencil"></i> Update Tracking</button><br><br>';

            if($countreceiving > 0){
               $gridColumn2=$UpdateButton.$trackreceiving;  
            } else{
                $gridColumn2=Html::button('<span class="glyphicon glyphicon-plus"></span> Add Referral Track', ['value'=>"/referrals/referraltrackreceiving/create?referralid=$model->referral_id", 'class' => 'btn btn-success','title' => Yii::t('app', "Referral Track Receiving Lab"),'id'=>'btnreceivedtrack','onclick'=>'addreceivedtrack(this.value,this.title)']);
            }
            
            
            $gridColumnResult=$gridColumnsResults;
         }
         else{
            $Uploadbtn=Html::button('<span class="glyphicon glyphicon-upload"></span> Upload Result', ['value'=>"/referrals/attachment/upload_result?referralid=$model->referral_id", 'class' => 'btn btn-success','title' => Yii::t('app', "Upload Result"),'id'=>'btnuploadresult','onclick'=>'addresult(this.value,this.title)']).'<br><br>'; 
            $Func="LoadModal('Update Tracking','/referrals/referraltracktesting/update?id=".$modelRefTracktesting->referraltracktesting_id."',true,500)";
            $UpdateButton='<button id="btnUpdate" onclick="'.$Func.'" type="button" style="float: left;padding-right:5px;margin-left: 5px" class="btn btn-primary"><i class="fa fa-pencil"></i> Update Tracking</button><br><br>';

            if($counttesting > 0){
                $gridColumn2=$UpdateButton.$tracktesting;
            } else{
                $gridColumn2=Html::button('<span class="glyphicon glyphicon-plus"></span> Add Referral Track', ['value'=>'/referrals/referraltracktesting/create?referralid='.$model->referral_id.'&receivingid='.$model->receiving_agency_id, 'class' => 'btn btn-success','title' => Yii::t('app', "Referral Track Testing/Calibration Lab"),'id'=>'btntestingtrack','onclick'=>'addtestingtrack(this.value,this.title)']);
            }
            $gridColumnResult=$Uploadbtn.$gridColumnsResults;
            
         } 
         
        echo TabsX::widget([
            'position' => TabsX::POS_ABOVE,
            'align' => TabsX::ALIGN_LEFT,
            'encodeLabels' => false,
            'id' => 'tab_referral',
            'items' => [
                [
                    'label' => 'Logs',
                    'content' => $this->renderAjax('_notification',['notificationDataProvider'=>$notificationDataProvider]),
                    'active' => $LogActive,
                    'options' => ['id' => 'log'],
                ],
                [
                    'label' => 'Results',
                    'content' => $gridColumnResult,
                    'active' => $ResultActive,
                    'options' => ['id' => 'result'],
                ],
                [
                    'label' => 'Referral Track',
                    'content' =>$gridColumn2,
                    'active' => $TrackActive,
                    'options' => ['id' => 'referral_track'],
                ]
            ],
        ]);
        ?>
        </div>
        </div>      
    </div>
    <?php } ?>
</div>

</div>

<script type="text/javascript">
    function addreceivedtrack(url,title){
        //alert(title);
       LoadModal(title,url,'true','600px');
   }
    function addtestingtrack(url,title){
       LoadModal(title,url,'true','600px');
   }
    
   function addresult(url,title){
       LoadModal(title,url,'true','600px');
   } 
   //upload slip
    function upload(url,title){
        $('.modal-title').html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }

    //placing bid
    function placebid(url,title){
        $('.modal-title').html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }

    function generateSampleCode(url,title){
        $('.modal-title').html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }

    /*function sendNotification(url,title){

        BootstrapDialog.show({
            title: "<span class='glyphicon glyphicon-folder-close'></span>&nbsp;&nbsp;" + title,
            message: "<div class='alert alert-danger' style='border:2px #ff3300 dotted;margin:auto;font-size:13px;text-align:justify;text-justify:inter-word;'>"
                +"<strong style='font-size:16px;'>Warning:</strong><br>"
                +"<ol>"
                +"<li>Make sure the selected laboratory is correct before you notify. Is it really Chem Lab, Micro Lab, Metro Lab, etc.?</li>"
                +"<li>If you are notifying to <strong><i>DOST-ITDI</i></strong> for chemical analysis, make sure you have selected either Organic Chemistry Laboratory (OCS) or Inorganic Chemistry Laboratory (ICS).</li>"
                +"</ol>"
                +"<p style='font-weight:bold;font-size:13px;'><span class='glyphicon glyphicon-info-sign' style='font-size:17px;'></span>&nbsp;If you need assistance, please contact the web administrator.</p>"
                +"</div>"
                +"<p class='note' style='margin:15px 0 0 15px;font-weight:bold;color:#0d47a1;font-size:14px;'>Are you sure you want to send notification to <span class='agency-name' style='color:#000000;'>"+title+"</span>?</p>",
            buttons: [
                {
                    label: 'Bid',
                    cssClass: 'btn-primary',
                    action: function(thisDialog){
                        thisDialog.close();
                        $('.modal-title').html(title);
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
    }*/
</script>   
   