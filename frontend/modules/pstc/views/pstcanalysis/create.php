<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcanalysis */

$this->title = 'Create Pstcanalysis';
$this->params['breadcrumbs'][] = ['label' => 'Pstcanalyses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcanalysis-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sampleDataProvider' => $sampleDataProvider,
        'testcategory' => $testcategory,
        'sampletype' => $sampletype,
        'testname' => $testname,
        'methodrefDataProvider' => $methodrefDataProvider,
    ]) ?>

</div>
