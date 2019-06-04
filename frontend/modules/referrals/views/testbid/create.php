<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Testbid */

$this->title = 'Create Testbid';
$this->params['breadcrumbs'][] = ['label' => 'Testbids', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testbid-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
