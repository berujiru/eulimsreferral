<?php
/* @var $this yii\web\View */

use kartik\grid\GridView;



$api_url_get = "https://api3.onelab.ph/access/get-access-token?tk=&id=";

$api_url_post = "https://api3.onelab.ph/access/get-access-token?tk=&id=";
?>
<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        // 'pjax' => true,
        // 'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-products']],
        'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<span class="glyphicon glyphicon-book"></span> Sync',
            ],
        // 'exportConfig'=>$func->exportConfig("Customer Wallet", "customer_wallet", $Header),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             // 'customerwallet_id',
            [
                'attribute'=>'tblname',
                'header'=>'Table'
            ],
            [
                'attribute'=>'datetime',
                
            ],
            [
            	'attribute'=>'recordID',
            ],
           
             // ['class' => 'yii\grid\ActionColumn'],
            [ 
                'class' => kartik\grid\ActionColumn::className(),
                'contentOptions' => ['style' => 'width: 8.7%'],
                'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{sync}',
                'buttons'=>[
                    'add'=>function ($url, $model) {
                        // $CustomerName=$model->customer ? $model->customer->customer_name : '';
                        // $t = '/finance/customertransaction/create?customerwallet_id='.$model->customerwallet_id;
                        // return Html::button('<span class="glyphicon glyphicon-plus"></span>', ['value'=>Url::to($t), 'class' => 'btn btn-success btn-modal','title' => Yii::t('app', "Add Funds for ".$CustomerName),'name' => Yii::t('app', "Add Funds for <font color='#272727'>[<b>".$CustomerName."</b>]</font>")]);
                    },
                    'view'=>function ($url, $model) {
                        // $CustomerName=$model->customer ? $model->customer->customer_name : '';
                        // $t = '/finance/customerwallet/view?id='.$model->customerwallet_id;
                        // return Html::button('<span class="fa fa-eye"></span>', ['value'=>Url::to($t), 'class' => 'btn btn-primary btn-modal','title' => Yii::t('app', "View History for ".$CustomerName),'name' => Yii::t('app', "View History for  <font color='#272727'>[<b>".$CustomerName."</b>]</font>")]);
                    },
                    
                ],
            ],

        ],
    ]); 
    ?>
	
	<?php
    // This section will allow to popup a notification
    $session = Yii::$app->session;
    if ($session->isActive) {
        $session->open();
        if (isset($session['deletepopup'])) {
            $func->CrudAlert("Deleted Successfully","WARNING");
            unset($session['deletepopup']);
            $session->close();
        }
        if (isset($session['updatepopup'])) {
            $func->CrudAlert("Updated Successfully");
            unset($session['updatepopup']);
            $session->close();
        }
        if (isset($session['savepopup'])) {
            $func->CrudAlert("Saved Successfully","SUCCESS",true);
            unset($session['savepopup']);
            $session->close();
        }
    }
    ?>
</div>
