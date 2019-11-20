<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\PstcanalysisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pstcanalyses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pstcanalysis-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pstcanalysis', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pstc_analysis_id',
            'pstc_sample_id',
            'rstl_id',
            'pstc_id',
            'testname_id',
            //'testname',
            //'method_id',
            //'method',
            //'reference:ntext',
            //'fee',
            //'quantity',
            //'is_package',
            //'is_package_name',
            //'local_analysis_id',
            //'local_sample_id',
            //'cancelled',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
