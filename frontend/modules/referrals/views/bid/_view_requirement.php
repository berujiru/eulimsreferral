<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\referral\Bid */
?>

<div class="view-bid-form">
	<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'bid_id',
            //'referral_id',
            //'bidder_agency_id',
            //'sample_requirements:ntext',
            //'bid_amount',
            //'remarks',
            //'estimated_due',
			[
				'label' => 'Sample Requirements',
				//'attribute' => 'sample_requirements:ntext',
				'value' => $count == 0 ? nl2br($_SESSION['addbid_requirement_'.$referralId]['sample_requirements']) : $model->sample_requirements,
				'format' => 'raw',
			],
			[
				'label' => 'Remarks',
				'value' => $count == 0 ? nl2br($_SESSION['addbid_requirement_'.$referralId]['remarks']) : $model->remarks,
				'format' => 'raw',
			],
			['attribute' => 'estimated_due', 
				'label' => 'Estimated Due Date',
				'value' => $count == 0 ? $_SESSION['addbid_requirement_'.$referralId]['estimated_due'] : date('F j, Y',strtotime($model->estimated_due)),
				'format' => 'raw',
			],
            //'seen',
            //'seen_date',
            //'created_at',
			[
				//'attribute' => 'created_at', 
				'label' => 'Date Created',
				'value' => $count == 0 ? '<i style="font-size:13px;color:#FF0000;">Not yet submitted</i>' : date('F j, Y h:i:s A',strtotime($model->created_at)),
				'format' => 'raw',
			],
            //'updated_at',
        ],
    ]) ?>
</div>
