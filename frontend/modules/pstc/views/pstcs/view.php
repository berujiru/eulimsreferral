<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstc */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pstcs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstc-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pstc_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pstc_id], [
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
            'pstc_id',
            'agency_id',
            'name',
        ],
    ]) ?>

</div>
