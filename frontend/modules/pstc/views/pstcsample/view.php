<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcsample */

$this->title = $model->pstc_sample_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcsamples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcsample-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pstc_sample_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pstc_sample_id], [
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
            'pstc_sample_id',
            'pstc_request_id',
            'rstl_id',
            'pstc_id',
            'testcategory_id',
            'sampletype_id',
            'sample_name',
            'sample_description:ntext',
            'sample_code',
            'local_sample_id',
            'local_request_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
