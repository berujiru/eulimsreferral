<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\system\ApiSettings */

$this->title = $model->api_settings_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-settings-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'api_settings_id',  
                'label'=>'Settings ID'
            ],
            [
                'attribute'=>'rstl_id',  
                'label'=>'RSTL',
                'value'=>$model->rstl->name
            ],
            'api_url:url',
            'get_token_url:url',
            'request_token',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    <div class="row" style="float: right;padding-right: 15px">
        <?= Html::Button('Close', ['class' => 'btn btn-default','id'=>'modalCancel','data-dismiss'=>'modal']) ?>
    </div>
</div>
