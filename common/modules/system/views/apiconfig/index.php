<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\components\Functions;
use common\models\system\Rstl;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\system\ApiSettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('app', 'API Configurations');;
$this->params['breadcrumbs'][] = ['label' => 'System', 'url' => ['/system']];
$this->params['breadcrumbs'][] = $this->title;

$func=new Functions();
$Header="Department of Science and Technology<br>";
$Header.="API Configuration List";
?>
<div class="api-settings-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'containerOptions' => ['style' => 'overflow-x: none!important','class'=>'kv-grid-container'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => false,
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="glyphicon glyphicon-cog"></i> API Settings',
            'before'=>"<button type='button' onclick='LoadModal(\"Create API Settings\",\"/system/apiconfig/create\")' class=\"btn btn-success\"><i class=\"fa fa-book-o\"></i> Create API Settings</button>",
        ],
        'pjax' => true, // pjax is set to always true for this demo
        'pjaxSettings' => [
            'options' => [
                    'enablePushState' => false,
              ],
        ],
        'exportConfig'=>$func->exportConfig("API Configuration List", "API Settings", $Header),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'rstl_id',
                'hAlign'=>'left',
                'width'=>'20%',
                'label' => 'RSTL',
                'value' => function($model) {
                    return $model->rstl->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Rstl::find()->asArray()->all(), 'rstl_id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'RSTL', 'id' => 'grid-products-search-category_type_id']
            ],
            'api_url:url',
            'get_token_url:url',
            'request_token',
            // 'created_at',
            // 'updated_at',

            [
                'class' => kartik\grid\ActionColumn::className(),
                'template' => "{view}{update}{delete}",
                'buttons' => [
                    
                    'view' => function ($url, $model){
                        return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value' => '/system/apiconfig/view?id=' . $model->api_settings_id,'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary', 'title' => Yii::t('app', "View API Settings")]);
                    },
                    'update' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => '/system/apiconfig/update?id=' . $model->api_settings_id, 'onclick' => 'LoadModal(this.title, this.value);', 'class' => 'btn btn-success', 'title' => Yii::t('app', "Update API Settings")]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
