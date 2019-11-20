<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Loginlogs */

$this->title = 'Create Loginlogs';
$this->params['breadcrumbs'][] = ['label' => 'Loginlogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loginlogs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
