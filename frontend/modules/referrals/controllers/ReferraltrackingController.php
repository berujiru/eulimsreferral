<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Referraltracktesting;
use common\models\referral\Referraltrackreceiving;
use common\models\referral\Referral;
use common\models\referral\ReferralSearch;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use common\models\referral\Referraltracking;
use frontend\modules\referrals\template\Referralmonitoringsheet;


class ReferraltrackingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Referraltracking::find()->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model
        ]);
        if (Yii::$app->request->post()) {
            
            if(!empty(Yii::$app->request->post('from_date')) && !empty(Yii::$app->request->post('to_date')))
            {
                $date_from=Yii::$app->request->post('from_date');
                $date_to=Yii::$app->request->post('to_date');
            }
            else{
                $date_from=date('Y-m-d');
                $date_to=date('Y-m-d');
            }
            if(!empty(Yii::$app->request->post('referral_code')))
            {
                $referral_code=Yii::$app->request->post('referral_code');
                $referral=$this->getrefid($referral_code);
                $referral_id=$referral->referral_id;
                $referralmodel= Referraltracking::find()->where(['referral_id'=>$referral_id])->all();
            }
            else{
                $referralmodel= Referraltracking::find()->where(['between', 'referral_date_time', $date_from, $date_to])->all();
            }
            
            
            
           // echo $model->referral_code;
           // exit;
           // $datas=$this->tracking(5);
            $dataProvider = new ArrayDataProvider([
                'allModels' => $referralmodel,
               /* 'pagination'=> [
                    'pageSize' => 10,
                ],*/
            ]);
           /* echo "<pre>";
            print_r($data->referraltesting->courier_id);
            
            echo "</pre>"; */
            /*foreach($datas as $data){
                echo($data->referraltesting->courier_id);
            } */
            
            /* return $this->render('index', [
             'model' =>  $referralmodel,
            // 'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
             ]);*/
             
            $exporter = new Referralmonitoringsheet([
            'model'=>$referralmodel,
            ]);
        }
        else {
             return $this->render('index', [
             'model' =>  $model,    
             'dataProvider' => $dataProvider,
             ]);
           
        }
       
    }
    
    public function getrefid($referral_code) {
        $lists= Referral::find()->where(['referral_code'=>$referral_code])->one();
        return $lists;
    }
    public function tracking($referral_id) {
        $query= Referraltrackreceiving::find()->with('referraltesting')->where(['referral_id'=>$referral_id])->all();
        return $query;         
    }
    
     public function actionPrintview($id)
    {
      //find the record the testreport
      $tracking =$this->findModel($id);
      /*$var=$->getBankAccount();
      if($var['bank_name'] == ""){
         Yii::$app->session->setFlash('warning', 'Please configure Bank Details!');
          return $this->redirect(['/finance/op/view?id='.$id]); 
      }else{*/
          $exporter = new Referralmonitoringsheet([
            'model'=>$tracking,
           ]);
     // }
      //echo $id;
      //exit;
      
    }
    
    protected function findModel($id)
    {
        if ($model = Referraltracking::find()->where(['referral_id'=>$id])->one() !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
   
     public function actionSearch($rcode,$datefrom,$dateto)
    {
      /* echo $datefrom;
       echo $dateto;
       exit; */
           if(!empty($datefrom) && !empty($dateto))
            {
                $date_from=date("Y-m-d", strtotime($datefrom));
                $date_to=date("Y-m-d", strtotime($dateto));
            }
            else{
                $date_from=date('Y-m-d');
                $date_to=date('Y-m-d');
            }
            if(!empty($rcode))
            {
                $referral_code=$rcode;
                $referral=$this->getrefid($referral_code);
                $referral_id=$referral->referral_id;
                $referralmodel= Referraltracking::find()->where(['referral_id'=>$referral_id])->all();
            }
            else{
                $referralmodel= Referraltracking::find()->where(['between', 'referral_date_time', $date_from, $date_to])->all();
            }
            
           
            $dataProvider = new ArrayDataProvider([
                'allModels' => $referralmodel,
            ]);
             
            return $this->renderAjax('_monitoring', [
             'model' =>  $referralmodel,
             'dataProvider' => $dataProvider,
            ]);
    }

}

