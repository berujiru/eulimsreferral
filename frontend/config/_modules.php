<?php
use yii\helpers\ArrayHelper;
/* 
 * Project Name: eulims * 
 * Copyright(C)2017 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 12 29, 17 , 7:01:24 PM * 
 * Module: modules * 
 */
//register Module from ini files
$ModulesSetting=parse_ini_file(__DIR__.'/modules.ini',true);
//Merger Arrays
return ArrayHelper::merge($ModulesSetting, []);