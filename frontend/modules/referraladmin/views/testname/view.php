<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Testname */

$this->title = $model->testname_id;
$this->params['breadcrumbs'][] = ['label' => 'Testnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="testname-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'testname_id',
            'test_name',
            'active',
            'create_time',
            'update_time',
        ],
    ]) ?>

</div>
