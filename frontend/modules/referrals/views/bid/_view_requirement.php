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
            'sample_requirements:ntext',
            //'bid_amount',
            'remarks',
            //'estimated_due',
			['attribute' => 'estimated_due', 
				'label' => 'Estimated Due Date',
				'value' => date('F j, Y',strtotime($model->estimated_due)),
				'format' => 'raw',
			],
            //'seen',
            //'seen_date',
            //'created_at',
			['attribute' => 'created_at', 
				'label' => 'Date Created',
				'value' => date('F j, Y h:i:s A',strtotime($model->created_at)),
				'format' => 'raw',
			],
            //'updated_at',
        ],
    ]) ?>
</div>
