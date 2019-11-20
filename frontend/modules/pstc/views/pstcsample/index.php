<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\PstcsampleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pstcsamples';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcsample-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pstcsample', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pstc_sample_id',
            'pstc_request_id',
            'rstl_id',
            'pstc_id',
            'testcategory_id',
            //'sampletype_id',
            //'sample_name',
            //'sample_description:ntext',
            //'sample_code',
            //'local_sample_id',
            //'local_request_id',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
