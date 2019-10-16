<?php

use yii\web\JsExpression;
use yii\helpers\Url;
//use edofre\fullcalendar\Fullcalendar;
//use miloschuman\highcharts\Highcharts;
//use miloschuman\highcharts\HighchartsAsset;
use common\models\system\LoginForm;
use rmrevin\yii\fontawesome\FA;
use kartik\select2\Select2;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use kartik\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    .carousel-indicators {
        bottom:-30px;
    }

    .carousel-indicators li {
        border-color:#999;
        background-color:#ccc;
    }

    .carousel-inner {
        margin-bottom:20px;
    }

    .carousel-control.left .glyphicon {
        left: 0;
        margin-left: 0;
        display: none;
    }
    .carousel-control.right .glyphicon {
        right: 0;
        margin-right: 0;
        display: none;
    }


</style>
<?php if (!Yii::$app->user->isGuest) { ?>

<div class="site-index">
</div>

<?php } else { ?>
    <?php
    $model = new LoginForm();
    echo $this->render('..\admin-lte\site\userdashboard.php', [
        'model' => $model,
    ]);
    ?>
<?php } ?>
