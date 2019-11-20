<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\PstcrespondSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pstcresponds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcrespond-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pstcrespond', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pstc_respond_id',
            'rstl_id',
            'pstc_id',
            'pstc_request_id',
            'request_ref_num',
            //'local_request_id',
            //'request_date_created',
            //'estimated_due_date',
            //'lab_id',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
