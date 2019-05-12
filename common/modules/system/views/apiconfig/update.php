<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\system\ApiSettings */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Api Settings',
]) . $model->api_settings_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->api_settings_id, 'url' => ['view', 'id' => $model->api_settings_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="api-settings-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
