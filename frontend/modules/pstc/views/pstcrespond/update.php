<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcrespond */

$this->title = 'Update Pstcrespond: ' . $model->pstc_respond_id;
$this->params['breadcrumbs'][] = ['label' => 'Pstcresponds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pstc_respond_id, 'url' => ['view', 'id' => $model->pstc_respond_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pstcrespond-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
