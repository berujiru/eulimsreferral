<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Visitedpage */

$this->title = 'Update Visitedpage: ' . $model->visited_page_id;
$this->params['breadcrumbs'][] = ['label' => 'Visitedpages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->visited_page_id, 'url' => ['view', 'id' => $model->visited_page_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="visitedpage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
