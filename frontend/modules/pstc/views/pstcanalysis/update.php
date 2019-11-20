<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcanalysis */

$this->title = 'Update Pstcanalysis: ' . $model->pstc_analysis_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcanalyses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pstc_analysis_id, 'url' => ['view', 'id' => $model->pstc_analysis_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pstcanalysis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sampleDataProvider' => $sampleDataProvider,
        'testcategory' => $testcategory,
        'methodrefDataProvider' => $methodrefDataProvider,
    ]) ?>

</div>
