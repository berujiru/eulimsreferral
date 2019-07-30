<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\CourierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Couriers';
$this->params['breadcrumbs'][] = $this->title;
$Button="{update}";
?>
<div class="courier-index">

    <p>
        <?=Html::button('<span class="glyphicon glyphicon-plus"></span> Add Courier', ['value'=>"create", 'class' => 'btn btn-success','title' => Yii::t('app', "Courier"),'id'=>'btncourier','onclick'=>'addcourier(this.value,this.title)']);?>
    </p

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'date_added',
			[
                'class' => kartik\grid\ActionColumn::className(),
                'template' => $Button,
                'buttons' => [
                   'update' => function ($url, $model) {
                        return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => '/referrals/courier/update?id=' . $model->courier_id, 'onclick' => 'LoadModal(this.title, this.value);', 'class' => 'btn btn-success', 'title' => Yii::t('app', "Update Courier")]);
                    }, 
                ],
            ],
        ],
    ]); ?>
</div>
<script type="text/javascript">
function addcourier(url,title){
   LoadModal(title,url,'true','600px');
}
</script>