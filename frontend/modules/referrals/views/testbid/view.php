<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Testbid */

$this->title = $model->analysis_bid_id;
$this->params['breadcrumbs'][] = ['label' => 'Testbids', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testbid-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->analysis_bid_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->analysis_bid_id], [
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
            'analysis_bid_id',
            'bidder_agency_id',
            'referral_id',
            'bid_id',
            'analysis_id',
            'fee',
        ],
    ]) ?>

</div>
