<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\MethodreferenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Method References';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="methodreference-index">

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
                'before'=> Html::button('<span class="glyphicon glyphicon-plus"></span> Create Method Reference', ['value'=>'/referraladmin/methodreference/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create New Method Reference"),'id'=>'btnMethodref','onclick'=>'addMethodref(this.value,this.title)']),
                
        ],    
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'method',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width: 30%;word-wrap: break-word;white-space:pre-line;'],
               
            ],
            [
                'attribute'=>'reference',
                'format' => 'raw',
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width: 70%;word-wrap: break-word;white-space:pre-line;'],
               
            ],
            [
                'attribute'=>'fee',
                'format' => 'raw',
                'value' => function($model) {
                    return number_format($model->fee, 2);
                    },
                'enableSorting' => false,
                'contentOptions' => ['style' => 'width: 70%;word-wrap: break-word;white-space:pre-line;'],
            
            ],

            ['class' => 'kartik\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 8.7%'],
            'template' => '{view}{update}{delete}',
            'buttons'=>[
                'view'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/referraladmin/methodreference/view','id'=>$model->methodreference_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Method Reference")]);
                },
                'update'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/referraladmin/methodreference/update','id'=>$model->methodreference_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Method Reference")]);
                },
                'delete'=>function ($url, $model) {
                    $urls = '/referraladmin/methodreference/delete?id='.$model->methodreference_id;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Method Reference','data-pjax'=>'0']);
                },
                ],
            ],
        ],
    ]); ?>
</div>

<script type="text/javascript">
    function addMethodref(url,title){
        LoadModal(title,url,'true','600px');
    }
  
</script>
