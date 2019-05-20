<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;
use yii\web\JsExpression;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Referral */

$this->title = empty($model->referral_code) ? $model->referral_id : $model->referral_code;
$this->params['breadcrumbs'][] = ['label' => 'Referrals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
</div>
