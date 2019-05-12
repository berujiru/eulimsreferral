<?php

/*
 * Project Name: eulims * 
 * Copyright(C)2018 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 05 22, 18 , 3:52:38 PM * 
 * Module: AccountcodeCollection * 
 */

namespace common\models\finance;

use yii\base\Model;

/**
 * Description of AccountcodeCollection
 *
 * @author mariano
 */
class AccountcodeCollection Extends Model
{
    //put your code here
    public $accountcode;
    public $natureofcollection;
    
    public function attributeLabels()
    {
        return [
            
            'accountcode' => 'Account Code',
            'natureofcollection' => 'Collection Type'
           
          
        ];
    }
}




