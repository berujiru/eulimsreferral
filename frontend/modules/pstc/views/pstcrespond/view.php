<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcrespond */

$this->title = $model->pstc_respond_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcresponds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcrespond-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pstc_respond_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pstc_respond_id], [
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
            'pstc_respond_id',
            'rstl_id',
            'pstc_id',
            'pstc_request_id',
            'request_ref_num',
            'local_request_id',
            'request_date_created',
            'estimated_due_date',
            'lab_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
