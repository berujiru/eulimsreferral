<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Visitedpage */

$this->title = $model->visited_page_id;
$this->params['breadcrumbs'][] = ['label' => 'Visitedpages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visitedpage-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->visited_page_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->visited_page_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'visited_page_id',
            'home_url:url',
            'module',
            'controller',
            'action',
            'user_id',
            'rstl_id',
            'pstc_id',
            'date_visited',
        ],
    ]) ?>

</div>
