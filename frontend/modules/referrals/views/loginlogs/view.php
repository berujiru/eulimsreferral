<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Loginlogs */

$this->title = $model->loginlogs_id;
$this->params['breadcrumbs'][] = ['label' => 'Loginlogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loginlogs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->loginlogs_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->loginlogs_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'loginlogs_id',
            'user_id',
            'agency_id',
            'login_date',
        ],
    ]) ?>

</div>
