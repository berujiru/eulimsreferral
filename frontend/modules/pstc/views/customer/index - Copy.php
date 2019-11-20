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

    <h1><?= Html::encode($this->title) ?></h1>
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
			'customer_code',
            'customer_name',
			'head',
			'tel',
		],
		'toolbar' => [
            'content'=> Html::a('<i class="glyphicon glyphicon-repeat"></i> Refresh Grid', [Url::to(['/referrals/customer'])], [
				'class' => 'btn btn-default', 
				'title' => 'Refresh Grid'
			]),
        ],
	]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'customer_id',
            //'rstl_id',
            'customer_code',
            'customer_name',
			//'classification_id',
            //'latitude',
            //'longitude',
            //'head',
            //'barangay_id',
            //'address',
            //'tel',
            //'fax',
            //'email:email',
            //'customer_type_id',
            //'business_nature_id',
            //'industrytype_id',
            //'created_at',
            //'customer_old_id',
            //'Oldcolumn_municipalitycity_id',
            //'Oldcolumn_district',
            //'local_customer_id',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
