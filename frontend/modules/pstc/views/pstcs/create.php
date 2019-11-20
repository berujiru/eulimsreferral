<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstc */

$this->title = 'Create Pstc';
$this->params['breadcrumbs'][] = ['label' => 'Pstcs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstc-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
