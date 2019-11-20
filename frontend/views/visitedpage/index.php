<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\VisitedpageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Visitedpages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visitedpage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Visitedpage', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'visited_page_id',
            'home_url:url',
            'module',
            'controller',
            'action',
            //'user_id',
            //'rstl_id',
            //'pstc_id',
            //'date_visited',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
