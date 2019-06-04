<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\referral\Referraltrackreceiving */

//$this->title = 'Create Referraltrackreceiving';
$this->params['breadcrumbs'][] = ['label' => 'Referraltrackreceivings', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referraltrackreceiving-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
