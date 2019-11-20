<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Sampletypetestname */

$this->title = $model->sampletypetestname_id;
$this->params['breadcrumbs'][] = ['label' => 'Sampletypetestnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sampletypetestname-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sampletypetestname_id',
            'sampletype_id',
            'testname_id',
            'added_by',
            'date_added',
        ],
    ]) ?>

</div>
