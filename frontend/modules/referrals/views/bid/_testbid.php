<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
//use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Bid */

?>			
<?php			
			$testbidgridColumns = [
            [
                'attribute'=>'sample_name',
                'header'=>'Sample',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                'width' => '30%',
            ],
            /*[
                'attribute'=>'sample_code',
                'header'=>'Sample Code',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
            ],*/
            [
                'attribute'=>'test_name',
                'format' => 'raw',
                'header'=>'Test/ Calibration Requested',
                'contentOptions' => ['style' => 'width: 15%;word-wrap: break-word;white-space:pre-line;'],
                'enableSorting' => false,
                'width' => '30%',
            ],
            [
                'attribute'=>'method',
                'format' => 'raw',
                'header'=>'Test Method',
                'enableSorting' => false,  
                'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
                'pageSummary' => '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>',
                'width' => '30%',
            ],
			[
				'class' => 'kartik\grid\EditableColumn',
                'enableSorting' => false,
				//'refreshGrid'=>true,
				//'asPopover' => true,
				'attribute' => 'fee',
				'format' => 'raw',
                //'value'=>function($data){
					//return number_format($data['analysis_fee'],2);
					//return (isset($_SESSION['test_bid'][$data['analysis_id']]) == $data['analysis_id']) ? $_SESSION['analysis_fee'] : '0';
					//foreach($_SESSION['test_bid'] as $item) {
                    //    while(list($key, $value) = each($item)) {
                    //        if($key == "analysis_id" && $value == $data['analysis_id']) {
                                // display session analysis_fee
					//			return $value;
                                
                    //      } // close if condition
                    //    } // close while loop
                    //} // close foreach loop
                //},
				'editableOptions' => [
					'header' => 'Analysis Fee', 
					'size'=>'s',
					'inputType' => \kartik\editable\Editable::INPUT_TEXT,
					'options' => [
						'pluginOptions' => ['min' => 1]
					],
					'name'=>'analysis_fee',
					'placement'  => 'left',
					'formOptions'=>['action' => ['/referrals/bid/inserttest_bid']],
				],
				'format'=>['decimal', 2],
				'hAlign' => 'right', 
				'vAlign' => 'middle',
				'width' => '10%',
				//'format' => ['decimal', 2],
				//'pageSummary' => true
                //'pageSummary'=> function () use ($subtotal,$discounted,$total,$countSample) {
                'pageSummary'=> function () use ($subtotal,$referralId,$discounted,$total) {
                    $testbidRefId = 'test_bids_'.$referralId;
                    if(isset($_SESSION[$testbidRefId])){
                        return  '<div id="subtotal">₱'.number_format($subtotal, 2).'</div><div id="discount">₱'.number_format($discounted, 2).'</div><div id="total"><b>₱'.number_format($total, 2).'</b></div>';
                    } else {
                        return '';
                    }
                },
			],
        ];
            echo GridView::widget([
                'id' => 'testbid-grid',
                'responsive'=>true,
                'dataProvider'=> $testbidDataProvider,
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
                    'heading'=>'<h3 class="panel-title">Bid</h3>',
                    'type'=>'primary',
                    'before'=> null,
                   'after'=> false,
                   //'footer'=>$actionButtonConfirm.$actionButtonSaveLocal,
                ],
                'columns' => $testbidgridColumns,
                'toolbar' => [
                    //'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['referral/view','id'=>$request['referral_id'],'notice_id'=>$notification['notification_id']])], [
                     //           'class' => 'btn btn-default', 
                    //            'title' => 'Refresh Grid'
                    //        ]),
                ],
            ]);