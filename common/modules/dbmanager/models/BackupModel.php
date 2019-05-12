<?php
namespace common\modules\dbmanager\models;
/*
 * Quarks Digital Solutions
 * Website Developed by: Nolan F. Sunico
 * Date Created: 4 Dec, 2018
 * Time Created: 11:48:49 PM
 * Module: BackupModel
 * Project: Port_Management_System.
 */

/**
 * Description of BackupModel
 *
 * @author Programmer
 */
class BackupModel extends \yii\base\Model{
    public $backupfiles;
    public $backupdatabase;
    public $database;
    public $extension;
    public $download;
    
    public function rules()
    {
        return [
          [['extension','database'], 'required'],
          [['backupfiles', 'backupdatabase', 'download'], 'integer'],
        ];
    }
}
