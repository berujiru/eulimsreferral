<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\LabSampletype */

$this->title = 'Update Lab Sampletype: ' . $model->labsampletype_id;
$this->params['breadcrumbs'][] = ['label' => 'Lab Sampletypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->labsampletype_id, 'url' => ['view', 'id' => $model->labsampletype_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lab-sampletype-update">
    <?= $this->render('_form', [
        'model' => $model,
        'lablist' => $lablist,
        'sampletypelist' => $sampletypelist
    ]) ?>

</div>
