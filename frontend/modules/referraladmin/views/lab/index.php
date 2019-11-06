<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referraladmin\LabSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laboratory';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lab-index">
      
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                'before'=> Html::button('<span class="glyphicon glyphicon-plus"></span> Create Laboratory', ['value'=>'/referraladmin/lab/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create Laboratory"),'id'=>'btnLab','onclick'=>'addLab(this.value,this.title)']),
               
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           
            [
                'attribute' =>  'labname',
                'label' => 'Laboratory Name'
            ],
            [
                'attribute' =>  'labcode',
                'label' => 'Laboratory Code'
            ],
            [
                'attribute' => 'active',
                'label' => 'Status',
                'hAlign'=>'center',
                'format'=>'raw',
                'value' => function($model) {
                    if ($model->active==1)
                    {   
                        return "<span class='badge badge-success' style='width:80px!important;height:20px!important;'>Active</span>";
                    }else{
                        return "<span class='badge badge-default' style='width:80px!important;height:20px!important;'>Inactive</span>";
                    }
                    
                },
            ],
            ['class' => 'kartik\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 8.7%'],
            'template' => '{view}{update}{delete}',
            'buttons'=>[
                'view'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/referraladmin/lab/view','id'=>$model->lab_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Laboratory")]);
                },
                'update'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/referraladmin/lab/update','id'=>$model->lab_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Laboratory")]);
                },
                'delete'=>function ($url, $model) {
                    $urls = '/referraladmin/lab/delete?id='.$model->lab_id;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Laboratory','data-pjax'=>'0']);
                },
                ],
            ],
        ],
    ]); ?>


</div>
<script type="text/javascript">
    function addLab(url,title){
        LoadModal(title,url,'true','600px');
    }
  
</script>