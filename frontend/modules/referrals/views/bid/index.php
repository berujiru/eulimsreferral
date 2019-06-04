<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $searchModel common\models\referral\BidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bids';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bid-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Bid', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo "<button type='button' onclick='LoadModal(\"Create Referral Request\",\"/referrals/bid/create\")' class=\"btn btn-success\"><i class=\"fa fa-book-o\"></i> Create Referral Request</button>"; 

    echo Html::button('<span class="glyphicon glyphicon-upload"></span> Upload', ['value'=>Url::to(['/referrals/bid/create','referral_id'=>1]), 'onclick'=>'upload(this.value,this.title)', 'class' => 'btn btn-primary btn-xs','title' => 'Upload Deposit Slip']);

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bid_id',
            'referral_id',
            'bidder_agency_id',
            'sample_requirements:ntext',
            'bid_amount',
            //'remarks',
            //'estimated_due',
            //'seen',
            //'seen_date',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<script type="text/javascript">
   //upload slip
    function upload(url,title){
        $('.modal-title').html(title);
        $('#modal').modal('show')
            .find('#modalContent')
            .load(url);
    }
</script>  
