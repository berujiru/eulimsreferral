<?php

/*
 * Project Name: eulims * 
 * Copyright(C)2018 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 05 7, 18 , 3:10:24 PM * 
 * Module: myActionColumn * 
 */
namespace common\components\template;
use kartik\grid\ActionColumn;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use Closure;
use yii\grid\ActionColumn as YiiActionColumn;
/**
 * Description of myActionColumn
 *
 * @author OneLab
 */
class myActionColumn extends ActionColumn{
    //put your code here
    public function init(){
        parent::init();
        $this->deleteOptions=['class'=>'btn btn-success'];
    }
}
