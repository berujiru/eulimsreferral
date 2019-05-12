<?php
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;
use yii\web\JsExpression;
/* 
 * Project Name: eulims_ * 
 * Copyright(C)2019 Department of Science & Technology -IX * 
 * Developer: Eng'r Nolan F. Sunico  * 
 * 01 18, 19 , 8:58:38 AM * 
 * Module: _graphpage * 
 * 
 */

//var_dump($column);


?>


 <?php
                                                echo Highcharts::widget([
                                                    'id' => 'labColumnChart2',
                                                    'scripts' => [
                                                        'modules/exporting',
                                                        'themes/grid-light',
                                                    ],
                                                    'options' => [
                                                        'title' => [
                                                            'text' => 'Firms Assisted',
                                                        ],
                                                        'xAxis' => [
                                                            'title' => [
                                                                'text' => 'Year'
                                                            ],
                                                           'categories' =>$listYear,
                                                        ],
                                                        'yAxis' => [
                                                            'title' => [
                                                                'text' => 'No of Firms'
                                                            ]
                                                        ],
                                                        'labels' => [
                                                            'items' => [
                                                                [
                                                                    'style' => [
                                                                        'left' => '50px',
                                                                        'top' => '18px',
                                                                        'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                       'series' => $column
                                                    ]
                                                ]);
                                                ?>



