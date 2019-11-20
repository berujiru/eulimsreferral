<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Pstcsample */

$this->title = 'Create Pstcsample';
$this->params['breadcrumbs'][] = ['label' => 'Pstcsamples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcsample-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sampletemplate' => $sampletemplate,
    ]) ?>

</div>
