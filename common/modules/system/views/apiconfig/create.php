<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\system\ApiSettings */

$this->title = Yii::t('app', 'Create Api Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-settings-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
