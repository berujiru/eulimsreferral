<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Courier */

$this->title = 'Update Courier: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Couriers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->courier_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="courier-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
