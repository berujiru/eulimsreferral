<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Courier */

$this->title = 'Create Courier';
$this->params['breadcrumbs'][] = ['label' => 'Couriers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courier-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
