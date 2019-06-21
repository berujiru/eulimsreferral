
<?php
use kartik\grid\GridView; 

    $gridColumns = [
    [
        //'attribute'=>'receiving_agency_id',
        'attribute'=>'referral_code',
        'header'=>'Referral code',
        'format' => 'raw',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model) ? $model->referral_code : null;
        },
        'contentOptions' => [
            'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;'
        ],
        'headerOptions' => ['style' => 'background-color:blue'], 
        'mergeHeader'=>true,
    ],
    ////////For Receiving Laboratory   
    [
        'attribute'=>'receiving_agency_id',
       // 'label'=>'Sample date',
        'enableSorting' => false,
        'contentOptions' => [
            'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;text-align: center'
        ],
        'value' => function($model) {
            return !empty($model) ? $model->agencyreceiving->name : null;
        },
        'headerOptions' => ['style' => 'background-color:#135cbf'],          
    ],   
    [
        'attribute'=>'sample_received_date',
       // 'label'=>'Sample date',
        'enableSorting' => false,
        /*'value' => function($model) {
            return !empty($model) ? $model->referraltrackreceivings->sample_received_date : null;
        },*/
        'value' => function($model) {
            return !empty($model) ?  date_format(date_create($model->sample_received_date),"Y-m-d") : null;
        },
        'headerOptions' => ['style' => 'background-color:#135cbf'],  
       
    ],    
    [
       'label'=>'No. of Samples',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model) ? count($model->samples) : null;
        },
        'headerOptions' => ['style' => 'background-color:#135cbf'],          
    ],  
     
    [
        'label'=>'Date Sample Dispatched via Courier',
        'enableSorting' => false,
        'contentOptions' => [
            'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;text-align: center'
        ],
        'value' => function($model) {
            return !empty($model->referraltrackreceivings) ? $model->referraltrackreceivings->shipping_date." ".$model::getComputecycle($model->sample_received_date,$model->referraltrackreceivings->shipping_date) : null;
        },
        'headerOptions' => ['style' => 'background-color:#135cbf'],          
    ],  
    [
        'label'=>'Courier Name',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model->referraltrackreceivings) ? $model->referraltrackreceivings->courier->name : null;
        },
        'headerOptions' => ['style' => 'background-color:#135cbf'],  
    ],    
    ////////For Testing Laboratory  
    [
        'attribute'=>'testing_agency_id',
       // 'label'=>'Sample date',
        'enableSorting' => false,
        /*'value' => function($model) {
            return !empty($model) ? $model->referraltrackreceivings->sample_received_date : null;
        },*/
        'contentOptions' => [
            'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;text-align: center'
        ],
        'value' => function($model) {
            return !empty($model) ? $model->agencytesting->name : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],
    [
        'label'=>'Date Sample Received(Courier)',
        'enableSorting' => false,
        'format'=>'raw',
        'contentOptions' => [
            'style'=>'max-width:70px; overflow: auto; white-space: normal; word-wrap: break-word;text-align: center'
        ],
        'value' => function($model) {
            return !empty($model->referraltracktestings) ? $model->referraltracktestings->date_received_courier."<br />".$model::getComputecycle($model->referraltrackreceivings->shipping_date,$model->referraltracktestings->date_received_courier) : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],
    [
       'label'=>'No. of Samples',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model) ? count($model->samples) : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],  
    [
       'label'=>"Date Sample Analyzed/ Calibrated",
        'enableSorting' => false,
        'format'=>'raw',
         'contentOptions' => [
            'style'=>'max-width:100px; overflow: auto; white-space: normal; word-wrap: break-word;text-align: center'
        ],
        'value' => function($model) {
            return !empty($model->referraltracktestings) ? $model->referraltracktestings->analysis_started."<br />".$model::getComputecycle($model->referraltracktestings->date_received_courier,$model->referraltracktestings->analysis_started) : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],
    [
       'label'=>'Date Sample Analysis / Calibration Completed',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model->referraltracktestings) ? $model->referraltracktestings->analysis_completed : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],
    [
       'label'=>'Date Test Report Uploaded',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model->referralattachment) ? date_format(date_create($model->referralattachment->upload_date),"Y-m-d") : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],
    [
       'label'=>'Date Calibration Specimen Received by RL', 
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model->referraltrackreceivings) ? $model->referraltrackreceivings->sample_received_date : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],
    [
       'label'=>'Date Calibration Specimen Mailed Back to RL',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model->referraltracktestings) ? $model->referraltracktestings->cal_specimen_send_date : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],
    [
       'label'=>'Courier Name',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model->referraltracktestings) ? $model->referraltracktestings->courier->name : null;
        },
        'headerOptions' => ['style' => 'background-color:#007700'],        
    ],
    [
       'label'=>'No. of Days (Duration)',
        'enableSorting' => false,
        'value' => function($model) {
            return !empty($model->referralattachment) ? $model->computeduration : null;
        },
        'headerOptions' => ['style' => 'background-color:blue'],      
             
    ],
    
];
    echo "<br>";
    echo GridView::widget([
       'id' => 'grid',
       'dataProvider'=> $dataProvider,
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ]
        ],
        'beforeHeader' => [
            [
                'columns' => [
                   ['content' => '', 'options' => ['class' => 'text-center warning', 'style' => 'border-style: solid;background-color:blue']],
                   ['content' => 'Section 1 (For Receving Lab)', 'options' => ['colspan' => 5, 'class' => 'text-center warning', 'style' => 'border-style: solid;background-color:#135cbf']],
                   ['content' => 'Section 2 (For Testing/Calibration Lab)', 'options' => ['colspan' => 9, 'class' => 'text-center warning', 'style' => 'border-style: solid;background-color:#007700']],
                   ['content' => '', 'options' => ['class' => 'text-center warning', 'style' => 'border-style: solid;background-color:blue']],
                ],
            ]
             
        ],
        'responsive'=>true,
        'striped'=>true,
        'hover'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><center><b>DOST-IX - Sample Referral Monitoring</b></center></h3>',
            'type'=>'primary',
            'before'=>null,
            'after'=>false,
        ],
        'columns' => $gridColumns,
              
    ]);
?>