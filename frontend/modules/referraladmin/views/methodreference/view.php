<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Methodreference */

$this->title = $model->methodreference_id;
$this->params['breadcrumbs'][] = ['label' => 'Methodreferences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="methodreference-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'methodreference_id',
            'method',
            'reference',
            'fee',
            'create_time',
            'update_time',
        ],
    ]) ?>

</div>
