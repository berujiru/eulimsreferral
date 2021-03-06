<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Sampletypetestname */

$this->title = 'Update Sampletypetestname: ' . $model->sampletypetestname_id;
$this->params['breadcrumbs'][] = ['label' => 'Sampletypetestnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sampletypetestname_id, 'url' => ['view', 'id' => $model->sampletypetestname_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sampletypetestname-update">

    <?= $this->render('_form', [
        'model' => $model,
        'sampletypelist' => $sampletypelist,
        'testnamelist' => $testnamelist
    ]) ?>

</div>
