<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'customer-grid',
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Customers</h3>',
            'type'=>'primary',
            'after'=>false,
            //'before'=>"<span style='color:#000099;'><b>Note:</b> All referral requests with referral code are shown.</span>",
        ],
		'columns' => [
			[
                'attribute' => 'customer_code',
                'format' => 'raw',
                'headerOptions' => ['class' => 'text-center'],
            ],
			[
                'attribute' => 'customer_name',
                'format' => 'raw',
                'headerOptions' => ['class' => 'text-center'],
            ],
			[
				'header' => 'Agency Head',
                'attribute' => 'head',
                'format' => 'raw',
                'headerOptions' => ['class' => 'text-center'],
            ],
			[
				'header' => 'Telephone Number',
                'attribute' => 'tel',
                'format' => 'raw',
                'headerOptions' => ['class' => 'text-center'],
            ],
		],
		'toolbar' => [
            'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/referrals/customer'])], [
				'class' => 'btn btn-default', 
				'title' => 'Refresh Grid'
			]),
        ],
	]); ?>
</div>
