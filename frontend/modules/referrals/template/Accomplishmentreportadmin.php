<?php

namespace frontend\modules\referrals\template;

use Yii;
use yii2tech\spreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
//use common\models\referral\Agency;
 /**
* 
*/
class Accomplishmentreportadmin extends Spreadsheet
{

	/**
     * @var location the data provider for the view. This property is required.
     */
    public $location="";
    public $model; // model used for targeting specific cell for data placements
     //public $LOCATION =\Yii::$app->basePath.'\\modules\\reports\\modules\\lab\\templates\\';
    /**

	/**
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet|null $document spreadsheet document representation instance.
     */

	public function init(){
		$this->location = \Yii::$app->basePath.'/modules/referrals/template/';
                $this->loaddoc();
                
                $rstl_id=Yii::$app->user->identity->profile->rstl_id;
                $var= Agency::find()->where(['agency_id'=>$rstl_id])->one();
                $filename="$var->name".' - Sample Referral Monitoring ('.date('d-M-Y').')';
                $this->send($filename.'.xls');
        }

    public function loaddoc()
    {
       
        $this->setDocument(IOFactory::load($this->location."Monitoring.xls"));
      
        $row=5;
        $models=$this->model;
        foreach ($models as $model) {
             # loop each of the anlyses
           // foreach ($sample->analys  es as $analysis) {
                
                #displaying the analysis on a row in a table
                //For Receiving
                $this->getDocument()->getActiveSheet()->setCellValue('A'.$row, !empty($model) ? $model->referral_code : null);
                $this->getDocument()->getActiveSheet()->setCellValue('B'.$row, !empty($model) ? $model->agencyreceiving->name : null);
                $this->getDocument()->getActiveSheet()->setCellValue('C'.$row, !empty($model) ?  date_format(date_create($model->sample_received_date),"Y-m-d") : null);
                $this->getDocument()->getActiveSheet()->setCellValue('D'.$row, !empty($model) ? count($model->samples) : null);
                $this->getDocument()->getActiveSheet()->setCellValue('E'.$row, !empty($model->referraltrackreceivings) ? $model->referraltrackreceivings->shipping_date." ".$model::getComputecycle($model->sample_received_date,$model->referraltrackreceivings->shipping_date) : null);
                $this->getDocument()->getActiveSheet()->setCellValue('F'.$row, !empty($model->referraltrackreceivings) ? $model->referraltrackreceivings->courier->name : null);
                //For Testing
                $this->getDocument()->getActiveSheet()->setCellValue('G'.$row, !empty($model) ? $model->agencytesting->name : null);
                $this->getDocument()->getActiveSheet()->setCellValue('H'.$row, !empty($model->referraltracktestings) ? $model->referraltracktestings->date_received_courier.$model::getComputecycle($model->referraltrackreceivings->shipping_date,$model->referraltracktestings->date_received_courier) : null);
                $this->getDocument()->getActiveSheet()->setCellValue('I'.$row, !empty($model) ? count($model->samples) : null);
                $this->getDocument()->getActiveSheet()->setCellValue('J'.$row, !empty($model->referraltracktestings) ? $model->referraltracktestings->analysis_started.$model::getComputecycle($model->referraltracktestings->date_received_courier,$model->referraltracktestings->analysis_started) : null);
                $this->getDocument()->getActiveSheet()->setCellValue('K'.$row, !empty($model->referraltracktestings) ? $model->referraltracktestings->analysis_completed : null);
                $this->getDocument()->getActiveSheet()->setCellValue('L'.$row, !empty($model->referralattachment) ? date_format(date_create($model->referralattachment->upload_date),"Y-m-d") : null);
                $this->getDocument()->getActiveSheet()->setCellValue('M'.$row, !empty($model->referraltrackreceivings) ? $model->referraltrackreceivings->sample_received_date : null);
                $this->getDocument()->getActiveSheet()->setCellValue('N'.$row, !empty($model->referraltracktestings) ? $model->referraltracktestings->cal_specimen_send_date : null);
                $this->getDocument()->getActiveSheet()->setCellValue('O'.$row, !empty($model->referraltracktestings) ? $model->referraltracktestings->courier->name : null);
                $this->getDocument()->getActiveSheet()->setCellValue('P'.$row, !empty($model->referralattachment) ? $model->computeduration : null);
                $row++; 
         } 
         $row=$row+2;
         $row1=$row+1;
         $style =['font' => ['size' => 10,'bold' => true,'color' => ['rgb' => 'ff0000']]];
         $this->getDocument()->getActiveSheet()->getStyle("A$row:A$row1")->applyFromArray($style);

         $this->getDocument()->getActiveSheet()->setCellValue('A'.$row, '*Note: RL = Receiving Lab, "0000-00-00" means no input date');
         $this->getDocument()->getActiveSheet()->setCellValue('A'.$row1, '*Note: No. of Days (Duration) = Date Test Report Uploaded - Date Sample Received');
        
//        //loop each of each sample
//        foreach ($this->model->request->samples as $sample) {
//             # loop each of the anlyses
//            foreach ($sample->analyses as $analysis) {
//                
//                #displaying the analysis on a row in a table
//                
//                $this->getDocument()->getActiveSheet()->setCellValue('B'.$row, $analysis->testname); //testname or parameter
//                $this->getDocument()->getActiveSheet()->setCellValue('C'.$row, $analysis->method); //method
//
//                $this->getDocument()->getActiveSheet()->insertNewRowBefore($row+1, 1);
//                $row++; 
//            }
//         } 
//         $this->getDocument()->getActiveSheet()->removeRow($row);

         #set password
         $this->getDocument()->getActiveSheet()->getProtection()->setSheet(true);
         $this->getDocument()->getSecurity()->setLockWindows(true);
         $this->getDocument()->getSecurity()->setLockStructure(true);
         $this->getDocument()->getSecurity()->setWorkbookPassword("PhpSpreadsheet");

        // Parent::setDocument($document);
    }

     public function render()
    {
        //overrides the render so that it would do nothing with cdataactiveprovider
        return $this;
    }

   
}

?>