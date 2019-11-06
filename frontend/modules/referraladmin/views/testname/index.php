<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\TestnameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Test Names';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testname-index">

<?php $this->registerJsFile("/js/services/services.js"); ?>

   

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
            'before'=> Html::button('<span class="glyphicon glyphicon-plus"></span> Create Test Name', ['value'=>'/referraladmin/testname/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create New Test Name"),'id'=>'btnTestname','onclick'=>'addTestname(this.value,this.title)']),
                
        ],    
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'test_name',
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
            'create_time',
            'update_time',

            ['class' => 'kartik\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 8.7%'],
          //  'template' => $button,
          'template' => '{view}{update}{delete}',
            'buttons'=>[
                'view'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/referraladmin/testname/view','id'=>$model->testname_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Test Name")]);
                },
                'update'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/referraladmin/testname/update','id'=>$model->testname_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Test Name")]);
                },
                'delete'=>function ($url, $model) {
                    $urls = '/referraladmin/testname/delete?id='.$model->testname_id;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Test Name','data-pjax'=>'0']);
                },
                ],
            ],
        ],
    ]); ?>
</div>

<script type="text/javascript">
    function addTestname(url,title){
        LoadModal(title,url,'true','600px');
    }
</script>
