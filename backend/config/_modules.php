<?php

/* 
 * Project Name: eulims * 
 * Copyright(C)2017 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 12 29, 17 , 7:01:24 PM * 
 * Module: modules * 
 */
return [
    'admin' => [
        'class' => 'common\modules\admin\Module',
        //'class' => 'common\mdm\admin\Module',
    ],
    'system' => [
        'class' => 'backend\modules\system\Module',
    ],
   
    //'message' => [
    //       'class' => 'thyseus\message\Module',
    //       'userModelClass' => '\app\models\User', // your User model. Needs to be ActiveRecord.
    //],
];
