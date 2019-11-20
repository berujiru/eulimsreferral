<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcsample */

$this->title = 'Update Pstcsample: ' . $model->pstc_sample_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcsamples', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pstc_sample_id, 'url' => ['view', 'id' => $model->pstc_sample_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pstcsample-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sampletemplate' => $sampletemplate,
    ]) ?>

</div>
