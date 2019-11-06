<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\referral\Lab;
use common\models\referral\Sampletype;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referraladmin\LabSampletypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lab Sampletypes';
$this->params['breadcrumbs'][] = $this->title;

$lablist= ArrayHelper::map(Lab::find()->all(),'lab_id','labname');
$sampetypelist= ArrayHelper::map(Sampletype::find()->all(),'sampletype_id','type');
?>
<div class="lab-sampletype-index"> 

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
                'before'=> Html::button('<span class="glyphicon glyphicon-plus"></span> Create Laboratory Sample Type', ['value'=>'/referraladmin/labsampletype/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create Laboratory Sample Type"),'id'=>'btnLab','onclick'=>'addLabsampletype(this.value,this.title)']),
               
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'lab_id',
                'contentOptions' => ['style' => 'width: 8.7%'],
                'label' => 'Laboratory',
                'format' => 'raw',
                'width'=>'20%',
                'value' => function($model) {
                   if ($model->lab){
                      return $model->lab->labname;
                   }else{
                        return "";
                   }
                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $lablist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Laboratory', 'lab_id' => 'grid-products-search-lab_id']
            ],
            [
                'attribute' => 'sampletype_id',
                'label' => 'Sample Type',
                'format' => 'raw',
                'width'=>'20%',
                'value' => function($model) {

                    if ($model->sampletype){
                        return $model->sampletype->type;
                    }else{
                        return "";
                    }        
                },
               'filterType' => GridView::FILTER_SELECT2,
               'filter' => $sampetypelist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Sample Type', 'sampletype_id' => 'grid-products-search-sampletype_id']
            ],
            'date_added',
            'added_by',
            ['class' => 'kartik\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 8.7%'],
            'template' => '{view}{update}{delete}',
            'buttons'=>[
                'view'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/referraladmin/labsampletype/view','id'=>$model->labsampletype_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Lab Sampletype")]);
                },
                'update'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/referraladmin/labsampletype/update','id'=>$model->labsampletype_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Lab Sampletype")]);
                },
                'delete'=>function ($url, $model) {
                    $urls = '/referraladmin/labsampletype/delete?id='.$model->labsampletype_id;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Lab Sampletype','data-pjax'=>'0']);
                },
                ],
            ],
        ],
    ]); ?>
</div>
<script type="text/javascript">
    function addLabsampletype(url,title){
        LoadModal(title,url,'true','600px');
    }
  
</script>
