<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\TestnameMethod */

$this->title = $model->testname_method_id;
$this->params['breadcrumbs'][] = ['label' => 'Testname Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testname-method-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'testname_method_id',
            [
                'attribute' => 'testname_id',
                'value' => function($model) {

                    if ($model->testname){
                        return $model->testname->test_name;
                    }else{
                        return "";
                    }
                    
                }
            ],
            
            [
                'attribute' => 'methodreference_id',
                'value' => function($model) {

                    if ($model->methodreference){
                        return $model->methodreference->method;
                    }else{
                        return "";
                    }
                    
                }
            ],
            'added_by',
            'create_time',
            'update_time',
        ],
    ]) ?>

</div>
