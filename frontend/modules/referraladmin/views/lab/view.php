<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Lab */

$this->title = $model->lab_id;
$this->params['breadcrumbs'][] = ['label' => 'Labs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lab-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'lab_id',
            [
                'attribute' => 'labname',
                'label' => 'Laboratory Name'
            ],
            [
                'attribute' => 'labcode',
                'label' => 'Laboratory Code'
            ],
            [
                'attribute' => 'active',
                'label' => 'Status',
                'format'=>'raw',
                'value' => function($model) {
                    if ($model->active==1)
                    {   
                        return "<span class='badge badge-success' style='width:80px!important;height:20px!important;'>Active</span>";
                    }else{
                        return "<span class='badge badge-default' style='width:80px!important;height:20px!important;'>Inactive</span>";
                    }
                    
                },
            ]
        ],
    ]) ?>

</div>
