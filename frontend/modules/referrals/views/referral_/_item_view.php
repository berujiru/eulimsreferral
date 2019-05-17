<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!--
<article class="list-item col-sm-12" data-key="<?= $data->notification_id ?>">
    <h3><?= Html::encode($data->notification_id); ?></h3>
    <figure>
        <?= Html::encode($data->recipient_id); ?>
    </figure>
	<figure>
        <?= Html::encode($data->sender_id); ?>
    </figure>
	<figure>
        <?= Html::encode($data->sender_name); ?>
    </figure>
</article>
-->
<li id="notification_li">
<span id="notification_count">3</span>
<a href="#" id="notificationLink">Notifications</a>

<div id="notificationContainer">
<div id="notificationTitle">Notifications</div>
<div id="notificationsBody" class="notifications">
<ul><a>function</a></ul>
<ul><a>function</a></ul>
<ul><a>function</a></ul>
<ul><a>function</a></ul>
<ul><a>function</a></ul>
<ul><a>function</a></ul>
<ul><a>function</a></ul>
</div>
<div id="notificationFooter"><a href="#">See All</a></div>
</div>

</li>
<div class="alert alert-primary">
  <strong>Info!</strong> Indicates a neutral informative change or action.
</div>

<style type="text/css">
#notification_li
{
position:relative
}
#notificationContainer 
{
background-color: #fff;
border: 1px solid rgba(100, 100, 100, .4);
-webkit-box-shadow: 0 3px 8px rgba(0, 0, 0, .25);
overflow: visible;
position: absolute;
top: 30px;
margin-left: -170px;
width: 400px;
z-index: -1;
display: none; // Enable this after jquery implementation 
}
// Popup Arrow
#notificationContainer:before {
content: '';
display: block;
position: absolute;
width: 0;
height: 0;
color: transparent;
border: 10px solid black;
border-color: transparent transparent white;
margin-top: -20px;
margin-left: 188px;
}
#notificationTitle
{
font-weight: bold;
padding: 8px;
font-size: 13px;
background-color: #ffffff;
position: fixed;
z-index: 1000;
width: 384px;
border-bottom: 1px solid #dddddd;
}
#notificationsBody
{
padding: 33px 0px 0px 0px !important;
min-height:300px;
}
#notificationFooter
{
background-color: #e9eaed;
text-align: center;
font-weight: bold;
padding: 8px;
font-size: 12px;
border-top: 1px solid #dddddd;
}
#nav{list-style:none;margin: 0px;padding: 0px;}
#nav li {
float: left;
margin-right: 20px;
font-size: 14px;
font-weight:bold;
}
#nav li a{color:#333333;text-decoration:none}
#nav li a:hover{color:#006699;text-decoration:none}
#notification_count
{
padding: 3px 7px 3px 7px;
background: #cc0000;
color: #ffffff;
font-weight: bold;
margin-left: 77px;
border-radius: 9px;
-moz-border-radius: 9px;
-webkit-border-radius: 9px;
position: absolute;
margin-top: -11px;
font-size: 11px;
}
</style>
<!--<script type="text/javascript" src="js/jquery.min.1.9.js"></script>-->
<script type="text/javascript" >
$(document).ready(function()
{
$("#notificationLink").click(function()
{
$("#notificationContainer").fadeToggle(300);
$("#notification_count").fadeOut("slow");
return false;
});

//Document Click hiding the popup 
$(document).click(function()
{
$("#notificationContainer").hide();
});

//Popup on click
$("#notificationContainer").click(function()
{
return false;
});

});
</script>