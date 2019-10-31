<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\TestnameMethod */

$this->title = 'Update Testname Method: ' . $model->testname_method_id;
$this->params['breadcrumbs'][] = ['label' => 'Testname Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->testname_method_id, 'url' => ['view', 'id' => $model->testname_method_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="testname-method-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
