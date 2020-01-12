<?php 

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Reports';
//$this->params['breadcrumbs'][] = ['label' => 'Referrals', 'url' => ['/referrals']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="accomplishment-report-default-index">
    <div class="panel panel-primary">
        <div class="panel-heading" style="font-weight: bold;font-size: 15px;">Accomplishment Reports</div>
    <!-- <div class="panel-body">Panel Content</div> -->
        <div class="panel-body">
            <div class="accomplishment-image-loader" style="display: hidden;"></div>
            <h4></h4>
            <?php //echo CHtml::link(CHtml::image(Yii::app()->baseUrl . '/images/accompreportlab.png', 'Per Laboratory', array('class'=>'image-icon','title'=>'Accomplishment Report per Laboratory')),
                //        $this->createUrl('/admindb/accomplishments/laboratory'),array('target'=>'_blank')
            //)?>
            <?php
                echo Html::a(Html::img('@web/images/accompreportlab.png', ['class'=>'image-icon','alt'=>'Per Laboratory','title'=>'Accomplishment Report per Laboratory']), ['/reports/accomplishmentadmin/laboratory'], ['target' => '_blank']);

                echo Html::a(Html::img('@web/images/accompreportagency.png', ['class'=>'image-icon','alt'=>'Per Agency','title'=>'Accomplishment Report per Agency']), ['/reports/accomplishmentadmin/agency'], ['target' => '_blank']);

                echo Html::a(Html::img('@web/images/truck.png', ['class'=>'image-icon','alt'=>'Referral Tracking','title'=>'Referral Tracking']), ['/referrals/referraltracking'], ['target' => '_blank']);
            ?>
        </div>
    </div>
</div>
<style type="text/css">
/* Absolute Center Spinner */
.accomplishment-img-loader {
    position: fixed;
    z-index: 999;
    /*height: 2em;
    width: 2em;*/
    height: 64px;
    width: 64px;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-image: url('/images/img-png-loader64.png');
    background-repeat: no-repeat;
}
/* Transparent Overlay */
.accomplishment-img-loader:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.3);
}
.image-icon {
    height: 140px;
    width: 160px;
    margin-right: 40px;
}
</style>