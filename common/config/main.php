<?php
$components = array_merge(
    require(__DIR__ . '/db.php'),
    require(__DIR__ . '/components.php')
);
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
        // 'datecontrol' =>  [
        //     'class' => 'kartik\datecontrol\Module'
        // ],
        'profile' => [
            'class' => 'common\modules\profile\Module',
        ],
        'dbmanager' => [
            'class' => 'common\modules\dbmanager\Module',
            'path' => dirname(dirname(__DIR__)) . '/frontend/web/backups/',
        ],
        'pdfjs' => [
            'class' => '\yii2assets\pdfjs\Module',
            'waterMark'=>[
                'text'=>' EULIMS',
                'color'=> 'red',
                'alpha'=>'0.1'
            ]
        ],
        'reportico' => [
            'class' => 'reportico\reportico\Module',
            'controllerMap' => [
                'reportico' => 'reportico\reportico\controllers\ReporticoController',
                'mode' => 'reportico\reportico\controllers\ModeController',
                'ajax' => 'reportico\reportico\controllers\AjaxController',
            ]
        ],
    ],
    // 'as access' => [
    //     //'class' => 'mdm\admin\components\AccessControl',
    //     'class' => 'common\modules\admin\components\AccessControl',
    //     'allowActions' => [
    //         '/install/*',
    //         '/ajax/*',
    //         '/ajax/getlab',
    //         'site/*',
    //         '/admin/user/signup',
    //         '/lab/analysis/listtest',
    //     ]
    // ],
    'components' => $components
];
