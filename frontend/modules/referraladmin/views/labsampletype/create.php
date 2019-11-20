<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\LabSampletype */

$this->title = 'Create Lab Sampletype';
$this->params['breadcrumbs'][] = ['label' => 'Lab Sampletypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lab-sampletype-create">

    <?= $this->render('_form', [
        'model' => $model,
        'lablist' => $lablist,
        'sampletypelist' => $sampletypelist
    ]) ?>

</div>
