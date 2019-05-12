<?php
/*$params = array_merge(
    //require __DIR__ . '/../../common/config/params.php',
    //require __DIR__ . '/../../common/config/params-local.php',
    //require __DIR__ . '/params.php',
    //require __DIR__ . '/params-local.php'
);
*/
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'], 
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@console' =>  dirname(dirname(__DIR__)) . '/console',
        '@web/assets' => dirname(dirname(__DIR__)) . '/frontend/web/assets',
        '@webroot/assets' => dirname(dirname(__DIR__)) . '/frontend/web',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'templateFile' => '@jamband/schemadump/template.php',
        ],
        'schemadump' => [
            'class' => jamband\schemadump\SchemaDumpController::class,
            'db' => [
                'class' => yii\db\Connection::class,
                'dsn' => 'mysql:host=localhost;dbname=eulims',
                'username' => 'eulims',
                'password' => 'eulims',
            ],
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            //'class' => 'common\models\User',
            //'enableAutoLogin' => true,
            //'loginUrl' => ['admin/user/login'],
        ],
         'inventorydb'=>[
            'class' => 'yii\db\Connection',  
            'dsn' => 'mysql:host=localhost;dbname=eulims_inventory',
            'username' => 'eulims',
            'password' => 'eulims',
            //'username'=>'arisro9',
            //'password'=>'qwerty!@#$%', 
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_',
        ],
        'labdb'=>[
            'class' => 'yii\db\Connection',  
            'dsn' => 'mysql:host=localhost;dbname=eulims_lab',
            'username' => 'eulims',
            'password' => 'eulims',
            //'username'=>'arisro9',
            //'password'=>'qwerty!@#$%', 
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_',
        ],
        'financedb'=>[
            'class' => 'yii\db\Connection',  
            'dsn' => 'mysql:host=localhost;dbname=eulims_finance',
            'username' => 'eulims',
            'password' => 'eulims',
            //'username'=>'arisro9',
            //'password'=>'qwerty!@#$%', 
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_',
        ],
        

    ],
    //'params' => $params,
];
