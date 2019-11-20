<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\TestnameMethod */

$this->title = 'Create Testname Method';
$this->params['breadcrumbs'][] = ['label' => 'Testname Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testname-method-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
