<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Methodreference */

$this->title = 'Update Methodreference: ' . $model->methodreference_id;
$this->params['breadcrumbs'][] = ['label' => 'Methodreferences', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->methodreference_id, 'url' => ['view', 'id' => $model->methodreference_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="methodreference-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
