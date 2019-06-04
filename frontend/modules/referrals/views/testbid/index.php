<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\TestbidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Testbids';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testbid-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Testbid', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'test_bid_id',
            'bidder_agency_id',
            'referral_id',
            'bid_id',
            'analysis_id',
            //'fee',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
