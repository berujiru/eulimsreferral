<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\system\Rstl;
use common\models\system\User;
use common\models\lab\Lab;
use common\components\Functions;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'User Profile List';
$this->params['breadcrumbs'][] = $this->title;
$RstlList= ArrayHelper::map(Rstl::find()->all(),'rstl_id','name');
$LabList= ArrayHelper::map(lab::find()->all(),'lab_id','labname'); //Yii::$app->user->identity->user_id
if(Yii::$app->user->can('access-his-profile')){
    $UserList= ArrayHelper::map(User::findAll(['user_id'=>Yii::$app->user->identity->user_id]),'user_id','username');
}else{
    $UserList= ArrayHelper::map(User::find()->all(),'user_id','username');
}
if(!Yii::$app->user->can('can-delete-profile')){
   $Buttontemplate='{view}{update}';
}else{
   $Buttontemplate='{view}{update}{delete}'; 
}
if(Yii::$app->user->can("profile-full-access")){
    $NewProfileButton=Html::button("<i class='fa fa-newspaper-o'></i> Create New User Profile",[
        'class'=>'btn btn-success',
        'onclick'=>"LoadModal('Create Profile','/profile/create')"
    ]);
}else{
    $NewProfileButton='';
}
$func=new Functions();
$Header="Department of Science and Technology<br>";
$Header.="User Profile List";
$gridColumn = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'user_id',
        'label' => 'User',
         
        'value' => function($model) {
            return $model->user->username;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(User::find()->asArray()->all(), 'user_id', 'username'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'User', 'user_id' => 'grid-products-search-category_type_id']
    ],
    'lastname',
    'firstname',
    'middleinitial',
    [
        'attribute' => 'rstl_id',
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
    [
        'attribute' => 'lab_id',
        'label' => 'Laboratory',
        'value' => function($model) {
            return $model->lab->labname;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Lab::find()->asArray()->all(), 'lab_id', 'labname'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Laboratory', 'lab_id' => 'grid-products-search-category_type_id']
    ],
    // 'lab_id',
    [
        //'class' => 'yii\grid\ActionColumn'
        'class' => kartik\grid\ActionColumn::className(),
        'template' => $Buttontemplate,
        'buttons'=>[
            'view'=>function ($url, $model) {
                return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', ['value'=>'/profile/'.$model->profile_id, 'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-primary','title' => Yii::t('app', "View Profile")]);
            },
            'update'=>function ($url, $model) {
                return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value'=>'/profile/update/'.$model->profile_id,'onclick'=>'LoadModal(this.title, this.value);', 'class' => 'btn btn-success','title' => Yii::t('app', "Update Profile")]);
            },
            'delete'=>function($url, $model){
                 return Html::a("<span class='glyphicon glyphicon-trash'></span>", "/profile/delete/".$model->profile_id, [
                    "title"=>"Delete", 
                    "aria-label"=>"Delete",
                    "data-pjax"=>"0", 
                    "data-method"=>"post", 
                    "data-confirm"=>"Are you sure you want to deactivate '".$model->fullname."' Profile?",
                    "class"=>"btn btn-danger"
                ]);
            }
        ],
    ],
];
?>
<div class="profile-index">
    <div class="table-responsive">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => false,
            'hover' => true,
            'pjax' => true, // pjax is set to always true for this demo
            'pjaxSettings' => [
                'options' => [
                        'enablePushState' => false,
                  ],
            ],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                'before'=>$NewProfileButton
            ],
            'exportConfig'=>$func->exportConfig("Laboratory Request", "laboratory request", $Header),
            'columns' => $gridColumn,
        ]);
        ?>
    </div>
</div>
