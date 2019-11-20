<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcanalysis */

$this->title = $model->pstc_analysis_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcanalyses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcanalysis-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pstc_analysis_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pstc_analysis_id], [
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
            'pstc_analysis_id',
            'pstc_sample_id',
            'rstl_id',
            'pstc_id',
            'testname_id',
            'testname',
            'method_id',
            'method',
            'reference:ntext',
            'fee',
            'quantity',
            'is_package',
            'is_package_name',
            'local_analysis_id',
            'local_sample_id',
            'cancelled',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
