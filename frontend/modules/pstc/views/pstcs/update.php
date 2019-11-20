<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstc */

$this->title = 'Update Pstc: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pstcs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->pstc_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pstc-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
