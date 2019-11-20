<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\referral\Sampletype;
use common\models\referral\Testname;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\lab\SampletypetestnameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sampletypelist= ArrayHelper::map(Sampletype::find()->all(),'sampletype_id','type');
$testnamelist= ArrayHelper::map(Testname::find()->all(),'testname_id','test_name');

$this->title = 'Sample Type Test name';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sampletypetestname-index">

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
                'before'=> Html::button('<span class="glyphicon glyphicon-plus"></span> Create Sample Type Test Name', ['value'=>'/referraladmin/sampletypetestname/create', 'class' => 'btn btn-success','title' => Yii::t('app', "Create New Sample Type Test Name"),'id'=>'btnSttn','onclick'=>'addSttn(this.value,this.title)']),
                
        ],    
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sampletype_id',
                'label' => 'Sample Type',
                'value' => function($model) {

                    if ($model->sampletype){
                        return $model->sampletype->type;
                    }
                    else{
                        return "";
                    }
                    
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $sampletypelist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Sample Type', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            [
                'attribute' => 'testname_id',
                'label' => 'Test Name',
                'value' => function($model) {
                    if ($model->testname){
                        return $model->testname->test_name;
                    }else{
                        return "";
                    }
                    
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $testnamelist,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
               ],
               'filterInputOptions' => ['placeholder' => 'Test Name', 'testcategory_id' => 'grid-products-search-category_type_id']
            ],
            'added_by',

            ['class' => 'kartik\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 8.7%'],
          'template' => '{view}{update}{delete}',
            'buttons'=>[
                'view'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>Url::to(['/referraladmin/sampletypetestname/view','id'=>$model->sampletypetestname_id]), 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Sample Type Test Name")]);
                },
                'update'=>function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>Url::to(['/referraladmin/sampletypetestname/update','id'=>$model->sampletypetestname_id]),'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Sample Type Test Name")]);
                },
                'delete'=>function ($url, $model) {
                    $urls = '/referraladmin/sampletypetestname/delete?id='.$model->sampletypetestname_id;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $urls,['data-confirm'=>"Are you sure you want to delete this record?<b></b>", 'data-method'=>'post', 'class'=>'btn btn-danger','title'=>'Delete Sample Type Test Name?','data-pjax'=>'0']);
                },
                ],
           ],
        ],
    ]); ?>
</div>
<script type="text/javascript">
    function addSttn(url,title){
        LoadModal(title,url,'true','600px');
    }
</script>