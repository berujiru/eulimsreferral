<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Lab */

$this->title = 'Update Laboratory: ' . $model->lab_id;
$this->params['breadcrumbs'][] = ['label' => 'Labs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lab_id, 'url' => ['view', 'id' => $model->lab_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lab-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
