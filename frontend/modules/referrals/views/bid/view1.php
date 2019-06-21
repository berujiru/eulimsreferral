<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Url;
use common\components\ReferralFunctions;
//use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Bid */

?>
<div class="bid-view">
<?php //$form = ActiveForm::begin(); ?>
<div class="container">
    <?php
	$component = new ReferralFunctions();
	$rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
	
	//if(!isset($_SESSION['test_bid'])){
	//	$_SESSION['test_bid'] = [];
		//$_SESSION['test_bid'] = [1=>['analysis_id'=>$analysisId,'analysis_fee'=>$fee]];
		//$_SESSION['test_bid'] = [['analysis_id'=>$analysisId,'analysis_fee'=>$fee]];
	//}
	
/*echo Editable::widget([
    'model' => $model, 
    'attribute' => 'rating',
    'type' => 'primary',
    'size'=> 'lg',
    'inputType' => Editable::INPUT_RATING,
    'editableValueOptions' => ['class' => 'text-success h3']
]);*/
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
                    'value' => function($data){
                        return ($data->referral->lab_id == 2) ? "Sampling Date: <span style='color:#000077;'><b>".date("Y-m-d h:i A",strtotime($data->sampling_date))."</b></span>,&nbsp;".$data->description : $data->description;
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
                    //'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/referrals/referral/bid/temporary','referral_id'=>$referralId])], [
                    //            'class' => 'btn btn-default', 
                    //            'title' => 'Refresh Grid'
                    //        ]),
                ],
            ]);
		
        $analysisgridColumns = [
			[
				'class' => 'kartik\grid\SerialColumn',
				'width' => '5%',
			],
            [
                //'attribute'=>'sample_name',
                'header'=>'Sample Name',
				'value'=>function($model){
					return $model->sample->sample_name;
				},
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
				'width' => '30%',
            ],
            /*[
                //'attribute'=>'sample_code',
                'header'=>'Sample Code',
                'format' => 'raw',
				'value'=>function($model){
					return $model->sample->sample_code;
				},
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
            ],*/
            [
                //'attribute'=>'test_name',
				'header'=>'Test Name',
                'format' => 'raw',
                'header'=>'Test/ Calibration Requested',
				'value'=>function($model){
					return $model->testname->test_name;
				},
				'width' => '30%',
                'contentOptions' => ['style' => 'width: 15%;word-wrap: break-word;white-space:pre-line;'],
                'enableSorting' => false,
            ],
            [
                //'attribute'=>'method',
				'header'=>'Method',
                'format' => 'raw',
                'header'=>'Test Method',
				'value'=>function($model){
					return $model->methodreference->method;
				},
				'width' => '30%',
                'enableSorting' => false,  
                'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
               // 'pageSummary' => '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>',             
            ],
			//[
            //    'attribute'=>'fee',
                //'header'=>'Unit Price',
            //    'enableSorting' => false,
            //    'hAlign'=>'right',
            //    'format' => 'raw',
            //    'value'=>function($data){
            //        return number_format($data['analysis_fee'],2);
            //    },
            //    'contentOptions' => [
            //        'style'=>'max-width:80px; overflow: auto; white-space: normal; word-wrap: break-word;'
            //    ],
                /*'hAlign' => 'right', 
                'vAlign' => 'left',
                'width' => '7%',
                'format' => 'raw',
                'pageSummary'=> function () use ($subtotal,$discounted,$total,$countSample) {
                    if($countSample > 0){
                        return  '<div id="subtotal">₱'.number_format($subtotal, 2).'</div><div id="discount">₱'.number_format($discounted, 2).'</div><div id="total"><b>₱'.number_format($total, 2).'</b></div>';
                    } else {
                        return '';
                    }
                },*/
			//	'class' => 'kartik\grid\EditableColumn',
				//'attribute' => 'name',
				//'pageSummary' => 'Total',
				//'vAlign' => 'middle',
				//'width' => '210px',
				//'readonly' => function($model, $key, $index, $widget) {
				//	return (!$model->status); // do not allow editing of inactive records
				//},
		/*		'editableOptions' => [
					//'header' => 'Buy Amount',
					'name' => 'fee',
					//'inputType' => \kartik\editable\Editable::INPUT_SPIN,
					/*'options' => [
						'pluginOptions' => ['min' => 0, 'max' => 5000]
					]*/
					//'url' => '/referrals/temporary',
				//],
            //],
			//[
			//	'class' => 'kartik\grid\EditableColumn',
				//'refreshGrid'=>true,
				//'asPopover' => true,
			//	'attribute' => 'fee',
				/*'readonly' => function($model, $key, $index, $widget) {
					if($model->status == 2 || $model->orderofpayment->payment_mode_id==5 || $model->orderofpayment->payment_mode_id==6){
						return true;
					}
					else{
						return false;
					}
					 // do not allow editing of inactive records
				 },*/
			//	'format' => 'raw',
                //'value'=>function($data){
				//	return number_format($data['analysis_fee'],2);
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
			//	'editableOptions' => [
			//		'header' => 'Analysis Fee', 
			//		'size'=>'s',
			//		'inputType' => \kartik\editable\Editable::INPUT_TEXT,
			//		'options' => [
			//			'pluginOptions' => ['min' => 1]
			//		],
			//		'name'=>'analysis_fee',
			//		'placement'  => 'left',
			//		'formOptions'=>['action' => ['/referrals/bid/inserttest_bid']],
			//	],
				/*'afterInput'=>function ($form, $widget) use ($model, $index) {
					return $form->field($model, "color")->widget(\kartik\widgets\ColorInput::classname(), [
						'showDefaultPalette'=>false,
						'options'=>['id'=>"color-{$index}"],
						'pluginOptions'=>[
							'showPalette'=>true,
							'showPaletteOnly'=>true,
							'showSelectionPalette'=>true,
							'showAlpha'=>false,
							'allowEmpty'=>false,
							'preferredFormat'=>'name',
							'palette'=>[
								["white", "black", "grey", "silver", "gold", "brown"],
								["red", "orange", "yellow", "indigo", "maroon", "pink"],
								["blue", "green", "violet", "cyan", "magenta", "purple"],
							]
						],
					]);
				},*/
			//	'format'=>['decimal', 2],
			//	'hAlign' => 'left', 
			//	'vAlign' => 'middle',
			//	'width' => '10%',
				//'format' => ['decimal', 2],
				//'pageSummary' => true
			//],
			[
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{addfee}',
				'dropdown' => false,
				'dropdownOptions' => ['class' => 'pull-right'],
				'headerOptions' => ['class' => 'kartik-sheet-style'],
				'width' => '5%',
				//'format'=>['decimal', 2],
				'buttons' => [
					'addfee' => function ($url, $data) use ($referralId,$component,$rstlId) {
						$testbidRefId = 'test_bids_'.$referralId;
						if((isset($_SESSION[$testbidRefId]) && array_key_exists($data->analysis_id,$_SESSION[$testbidRefId]))){
							//return number_format();
							//if (array_key_exists($data->analysis_id,$_SESSION[$testbidRefId])){
								//foreach($_SESSION[$testbidRefId] as $item) {
									//return $item['analysis_id'];
								//}
								//return $data->analysis_id;
								return 'Done';
							//}
						} else {
							$checkTestbid = $component->checkTestbid($referralId,$data->analysis_id,$rstlId);
							if($checkTestbid > 0){
								return 'Done';
							} else {
								return Html::button('<span class="glyphicon glyphicon-plus"></span> Add Fee', ['value'=>Url::to(['/referrals/bid/inserttest_bid','referral_id'=>$referralId,'analysis_id'=>$data->analysis_id]),'onclick'=>'bid(this.value,this.title)','class' => 'btn btn-xs btn-primary','title' => 'Add Fee']);
							}
						}
					},
				],
			],
        ];
            echo GridView::widget([
                'id' => 'analysis-grid',
                'responsive'=>true,
                'dataProvider'=> $analysisDataProvider,
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
                    'before'=> ($countBid == 0 && !isset($_SESSION['addbid_requirement_'.$referralId]) ? Html::button('<span class="glyphicon glyphicon-plus"></span> Add Sample Requirements', ['value'=>Url::to(['/referrals/bid/addbid_requirement','referral_id'=>$referralId]),'onclick'=>'bid(this.value,this.title)','class' => 'btn btn-primary','title' => 'Add Sample Requirements']) : Html::button('<span class="glyphicon glyphicon-eye-open"></span> View your sample requirement', ['value'=>Url::to(['/referrals/bid/viewbid_requirement','referral_id'=>$referralId]),'onclick'=>'bid(this.value,this.title)','class' => 'btn btn-success','title' => 'View your sample requirement'])).'&nbsp;'.($countBid > 0 || !isset($_SESSION['addbid_requirement_'.$referralId]) ? '' : Html::button('<span class="glyphicon glyphicon-edit"></span>', ['value'=>Url::to(['/referrals/bid/updatebid_requirement','referral_id'=>$referralId]),'onclick'=>'bid(this.value,this.title)','class' => 'btn btn-primary','title' => 'Edit sample requirement'])),
                   'after'=> false,
                   //'footer'=>$actionButtonConfirm.$actionButtonSaveLocal,
                ],
                'columns' => $analysisgridColumns,
                'toolbar' => [
                    //'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['referral/view','id'=>$request['referral_id'],'notice_id'=>$notification['notification_id']])], [
                     //           'class' => 'btn btn-default', 
                    //            'title' => 'Refresh Grid'
                    //        ]),
                ],
            ]);
        ?>
		
			<div id="show-testbids">
				<?php
					//echo $this->render('_testbid', ['testbidDataProvider' => $testbidDataProvider,'model'=>$model]);
					//echo $this->render('_testbid', ['testbidDataProvider' => $testbidDataProvider,'subtotal'=>$subtotal,'discounted' => $discounted,'total'=>$total,'referralId'=>$referralId]);
					
				?>
				<?php			
			$testbidgridColumns = [
            [
                //'attribute'=>'sample_name',
                'header'=>'Sample',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                'width' => '30%',
				'value' => function($data){
					return !empty($data->analysis->sample->sample_name) ? $data->analysis->sample->sample_name : $data['sample_name'];
				},
            ],
            /*[
                'attribute'=>'sample_code',
                'header'=>'Sample Code',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
            ],*/
            [
                //'attribute'=>'test_name',
                'format' => 'raw',
                'header'=>'Test/ Calibration Requested',
                'contentOptions' => ['style' => 'width: 15%;word-wrap: break-word;white-space:pre-line;'],
                'enableSorting' => false,
                'width' => '30%',
				'value' => function($data){
					return !empty($data->analysis->testname->test_name) ? $data->analysis->testname->test_name : $data['test_name'];
				},
            ],
            [
                //'attribute'=>'method',
                'format' => 'raw',
                'header'=>'Test Method',
                'enableSorting' => false,  
                'contentOptions' => ['style' => 'width: 50%;word-wrap: break-word;white-space:pre-line;'],
                'pageSummary' => '<span style="float:right";>SUBTOTAL<BR>DISCOUNT<BR><B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL</B></span>',
                'width' => '30%',
				'value' => function($data){
					return !empty($data->analysis->methodreference->method) ? $data->analysis->methodreference->method : $data['method'];
				},
            ],
			[
				'class' => 'kartik\grid\EditableColumn',
                'enableSorting' => false,
				'refreshGrid'=>true,
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
				'readonly' => function($model, $key, $index, $widget) use ($referralId) {
					//$checkTestbid = $component->checkTestbid($referralId,$model->analysis_id,$rstlId);
					/*if($checkTestbid > 0){
						return true;
					}else{
						return false;
					}*/
					if(isset($_SESSION['test_bids_'.$referralId])){
						return false;
					} else {
						return true;
					}
				},
				'editableOptions' => [
					'header' => 'Analysis Fee', 
					'size'=>'s',
					'inputType' => \kartik\editable\Editable::INPUT_TEXT,
					'options' => [
						'pluginOptions' => ['min' => 1]
					],
					'name'=>'analysis_fee',
					'placement'  => 'left',
					'formOptions'=>['action' => ['/referrals/bid/update_analysis_fee?referral_id='.$referralId]],
				],
				'format'=>['decimal', 2],
				'hAlign' => 'right', 
				'vAlign' => 'middle',
				'width' => '10%',
				//'format' => ['decimal', 2],
				//'pageSummary' => true
                //'pageSummary'=> function () use ($subtotal,$discounted,$total,$countSample) {
                'pageSummary'=> function () use ($subtotal,$referralId,$discounted,$total,$countBid) {
                    $testbidRefId = 'test_bids_'.$referralId;
                    if(isset($_SESSION[$testbidRefId]) && $countBid == 0){
                        return  '<div id="subtotal">₱'.number_format($subtotal, 2).'</div><div id="discount">₱'.number_format($discounted, 2).'</div><div id="total"><b>₱'.number_format($total, 2).'</b></div>';
                    } elseif($countBid > 0){
						return  '<div id="subtotal">₱'.number_format($subtotal, 2).'</div><div id="discount">₱'.number_format($discounted, 2).'</div><div id="total"><b>₱'.number_format($total, 2).'</b></div>';
					} else {
                        return '';
                    }
                },
			],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{remove}',
                'dropdown' => false,
                'dropdownOptions' => ['class' => 'pull-right'],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'width' => '5%',
                //'format'=>['decimal', 2],
                'buttons' => [
                    'remove' => function ($url, $data) use ($referralId) {
                        /*$testbidRefId = 'test_bids_'.$referralId;
                        if(isset($_SESSION[$testbidRefId]) && array_key_exists($data['analysis_id'],$_SESSION[$testbidRefId])){
                            //return number_format();
                            //if (array_key_exists($data->analysis_id,$_SESSION[$testbidRefId])){
                                //foreach($_SESSION[$testbidRefId] as $item) {
                                    //return $item['analysis_id'];
                                //}
                                //return $data->analysis_id;
                                return 'Done';
                            //}
                        } else {
                            return Html::button('<span class="glyphicon glyphicon-plus"></span> Add Fee', ['value'=>Url::to(['/referrals/bid/inserttest_bid','referral_id'=>$referralId,'analysis_id'=>$data['analysis_id']]),'onclick'=>'insertbid(this.value,this.title)','class' => 'btn btn-xs btn-primary','title' => 'Add Fee']);
                        }*/
                        $testbidRefId = 'test_bids_'.$referralId;
                        if(isset($_SESSION[$testbidRefId])){
                            return Html::button('<span class="glyphicon glyphicon-remove"></span> Remove', ['value'=>Url::to(['/referrals/bid/remove_testbid','analysis_id'=>$data['analysis_id'],'referral_id'=>$referralId]),'onclick'=>'bid(this.value,this.title)','class' => 'btn btn-xs btn-danger','title' => 'Remove Bid']);
                            //return Html::button('<span class="glyphicon glyphicon-remove"></span> Remove', ['value'=>Url::to(['/referrals/bid/redirect','analysis_id'=>$data['analysis_id'],'referral_id'=>$referralId]),'onclick'=>'updateBid(this.value,this.title)','class' => 'btn btn-xs btn-danger','title' => 'Remove Bid']);
                        } else {
                            return 'Submitted';
                        }
                    },
                ],
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
?>
			</div>
		<?php
			echo $countBid > 0 ? '' : Html::button('<span class="glyphicon glyphicon-check"></span> Place Bid', ['value'=>Url::toRoute(['/referrals/bid/placebid','referral_id'=>$referralId]), 'onclick'=>'bid(this.value,this.title)', 'class' => 'btn btn-primary','title' => 'Place Bid']); 
		?>
    </div>
		
<div class="form-group">
        <!-- <? //= Html::submitButton('Save', ['class' => 'btn btn-success']) ?> -->
		<?php //if(isset($_SESSION)){
	//	echo "<pre>";
	//	print_r($_SESSION);
	//	echo "</pre>";
	//} ?>
    </div>

    <?php //ActiveForm::end(); ?>
	
	<?php
		//echo Html::button('<span class="glyphicon glyphicon-plus"></span> Place Bid', ['value'=>Url::toRoute(['/referrals/bid/inserttest_bid','referral_id'=>Yii::$app->request->get('referral_id'),'analysis_id'=>2]), 'onclick'=>'insertbid(this.value,this.title)', 'class' => 'btn btn-primary btn-xs','title' => 'Place Bid'])."<br>"; 
	?>
</div>

<script type="text/javascript">
    //placing bid
    function bid(url,title){
        $('.modal-title').html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }
</script>
<?php
//$id = isset($_POST['editableKey']) ? $_POST['editableKey'] : 0;
//$fee = isset($_POST['analysis_fee']) ? $_POST['analysis_fee'] : 0;
/*$this->registerJs("
	$('.kv-editable-submit').on('keypress click', function(e){
		//var key_id = $('#sample-analysis-grid').yiiGridView('getSelectedRows');
		//if(key_id.length > 0) {
		e.preventDefault();
		
		//var analysisId = $(\"input[name='editableKey']\").val();
		//var fee = $(\"input[name='analysis_fee']\").val();
		var analysisId = ".$id.";
		var fee = ".$fee.";
		if (e.which === 13 || e.type === 'click') {
			alert(analysisId+'---'+fee);
			$.ajax({
				url: '/referrals/bid/inserttest_bid0',
				//data: $('.kv-editable-form').serialize(),
				//data: $('form').serialize(),
				data: 'analysis_id=',
				type: 'POST',
				success: function (data) {
					$('#show-testbids').html(data);
					$('.image-loader').removeClass(\"img-loader\");
				},
				beforeSend: function (xhr) {
					$('.image-loader').addClass(\"img-loader\");
				}
			});
		}
		//} else {
		//	alertWarning.alert(\"<p class='text-danger' style='font-weight:bold;'>No sample selected!</p>\");
		//}
	});
");*/

?>
