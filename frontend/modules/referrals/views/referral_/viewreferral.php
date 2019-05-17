<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\components\Functions;
use common\components\ReferralComponent;
use common\models\lab\Cancelledrequest;
use common\models\lab\Discount;
use common\models\lab\Request;
use common\models\lab\Sample;
use common\models\lab\Sampletype;
use common\models\finance\Paymentitem;

use common\models\lab\Package;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;
use yii\web\JsExpression;
//use kop\y2sp\ScrollPager;
use yii\widgets\ListView;

//$Connection = Yii::$app->financedb;
//$func = new Functions();
//$referralcomp = new ReferralComponent();

$this->title = empty($request['referral_code']) ? $request['referral_id'] : $request['referral_code'];
$this->params['breadcrumbs'][] = ['label' => 'Referrals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="section-request">
<div class="request-view ">
    <div class="image-loader" style="display: none;"></div>
    <div class="container table-responsive">
        <?php
            echo DetailView::widget([
            'model'=>$model,
            'responsive'=>true,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=>'<i class="glyphicon glyphicon-book"></i> Referral Code ' . $request['referral_code'],
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
                            'value'=> $request['referral_code'],
                        ],
                        [
                            'label'=>'Customer / Agency',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && count($customer) > 0 ? $customer['customer_name'] : "",
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
                            'value'=> ($request['referral_date_time'] != "0000-00-00 00:00:00") ? Yii::$app->formatter->asDate($request['referral_date_time'], 'php:F j, Y h:i a') : "<i class='text-danger font-weight-bold h5'>Pending referral request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Address',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && count($customer) > 0 ? $customer['address'] : "",
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
                            'value'=> !empty($request['sample_received_date']) ? Yii::$app->formatter->asDate($request['sample_received_date'], 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>No sample received date</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Tel no.',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && count($customer) > 0 ? $customer['tel'] : "",
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
                            'value'=> ($request['report_due'] != "0000-00-00 00:00:00") ? Yii::$app->formatter->asDate($request['report_due'], 'php:F j, Y') : "<i class='text-danger font-weight-bold h5'>Pending referral request</i>",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Fax no.',
                            'format'=>'raw',
                            'value'=> $request['customer_id'] > 0 && count($customer) > 0 ? $customer['fax'] : "",
                            'valueColOptions'=>['style'=>'width:30%'], 
                            'displayOnly'=>true
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            //'attribute'=>'report_due',
                            'label'=>'Referred by',
                            'format'=>'raw',
                            'value'=> !empty($receiving_agency) ? $receiving_agency : null,
                            'displayOnly'=>true
                        ],
                        [
                            'label'=>'Referred to',
                            'format'=>'raw',
                            //'value'=>$model->customer ? $model->customer->fax : "",
                            'value'=> !empty($testing_agency) ? $testing_agency : null,
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
                            'value'=>function() use ($depositslip,$model,$request){
                                $link = '';
                                if($depositslip > 0){
                                    foreach ($depositslip as $deposit) {
                                        $link .= Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$deposit['filename'],'/referrals/attachment/download?request_id='.$request['local_request_id'].'&file='.$deposit['attachment_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Deposit Slip','target'=>'_self'])."<br>";
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
                            'value'=>function() use ($officialreceipt,$model,$request){
                                $link = '';
                                if($officialreceipt > 0){
                                    foreach ($officialreceipt as $or) {
                                        $link .= Html::a('<span class="glyphicon glyphicon-save-file"></span> '.$or['filename'],'/referrals/attachment/download?request_id='.$request['local_request_id'].'&file='.$or['attachment_id'], ['style'=>'font-size:12px;color:#000077;font-weight:bold;','title'=>'Download Official Receipt','target'=>'_self'])."<br>";
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
                            'format'=>'raw',
                            'value'=>$request['cro_receiving'],
                            'displayOnly'=>true,
                            'valueColOptions'=>['style'=>'width:30%']
                        ],
                        [
                            'label'=>'Conforme',
                            'value'=> $request['conforme'],
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
                    'value' => function($data) use ($request){
                        return ($request['lab_id'] == 2) ? "Sampling Date: <span style='color:#000077;'><b>".date("Y-m-d h:i A",strtotime($data['sampling_date']))."</b></span>,&nbsp;".$data['description'] : $data['description'];
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
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['referral/view','id'=>$request['referral_id']])], [
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
                'attribute'=>'sample_name',
                'header'=>'Sample',
                'format' => 'raw',
                'enableSorting' => false,
                /*'value' => function($model) {
                    //return $model->sample ? $model->sample->samplename : '-';
                },*/
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
               
            ],
            [
                'attribute'=>'sample_code',
                'header'=>'Sample Code',
                /*'value' => function($model) {
                    //return $model->sample ? $model->sample->sample_code : '-';
                },*/
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
            ],
            [
                'attribute'=>'test_name',
                'format' => 'raw',
                'header'=>'Test/ Calibration Requested',
                'contentOptions' => ['style' => 'width: 15%;word-wrap: break-word;white-space:pre-line;'],
                'enableSorting' => false,
            ],
            [
                'attribute'=>'method',
                'format' => 'raw',
                'header'=>'Test Method',
                'enableSorting' => false,  
                'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
                'pageSummary' => '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>',             
            ],
            [
                'attribute'=>'fee',
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
                    'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['referral/view','id'=>$request['referral_id']])], [
                                'class' => 'btn btn-default', 
                                'title' => 'Refresh Grid'
                            ]),
                ],
            ]);
        ?>
    </div>
</div>
</div>
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
    background-image: url('/images/img-loader64.gif');
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