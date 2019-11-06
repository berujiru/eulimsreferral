<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\LabSampletype */

$this->title = $model->labsampletype_id;
$this->params['breadcrumbs'][] = ['label' => 'Lab Sampletypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lab-sampletype-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'labsampletype_id',
            [
                'attribute' => 'lab_id',
                'format' => 'raw',
                'value' => function($model) {
                   if ($model->lab){
                      return $model->lab->labname;
                   }else{
                        return "";
                   }
                   
                }
            ],
            [
                'attribute' => 'sampletype_id',
                'format' => 'raw',
                'value' => function($model) {

                    if ($model->sampletype){
                        return $model->sampletype->type;
                    }else{
                        return "";
                    }        
                }
            ],
            'date_added',
            'added_by',
        ],
    ]) ?>

</div>
