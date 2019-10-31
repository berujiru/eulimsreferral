<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\LabSampletype */

$this->title = 'Create Lab Sampletype';
$this->params['breadcrumbs'][] = ['label' => 'Lab Sampletypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lab-sampletype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
