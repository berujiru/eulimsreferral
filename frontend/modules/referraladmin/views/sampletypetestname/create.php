<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referraladmin\Sampletypetestname */

$this->title = 'Create Sampletypetestname';
$this->params['breadcrumbs'][] = ['label' => 'Sampletypetestnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sampletypetestname-create">

    <?= $this->render('_form', [
        'model' => $model,
        'sampletypelist' => $sampletypelist,
        'testnamelist' => $testnamelist
    ]) ?>

</div>
