<?php

namespace frontend\modules\referrals\controllers;

use Yii;
use common\models\referral\Attachment;
use common\models\referral\AttachmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;
use common\models\referral\Referral;
//use common\components\ReferralComponent;
use common\components\ReferralFunctions;
use linslin\yii2\curl;
use frontend\modules\referrals\controllers\ReferralController;
/**
 * AttachmentController implements the CRUD actions for Attachment model.
 */
class AttachmentController extends Controller
{
	
	public $apiUrl = 'http://localhost/eulimsapi.onelab.ph';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Attachment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttachmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Attachment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Attachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Attachment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->attachment_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Attachment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->attachment_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Attachment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Attachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attachment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

	//upload Deposit Slip
	public function actionUpload_deposit()
    {
        set_time_limit(120);
        if(Yii::$app->request->get('referral_id')){
            $referralId = (int) Yii::$app->request->get('referral_id');
            $referral = $this->findReferral($referralId);
        } else {
            Yii::$app->session->setFlash('error', "Referral ID not valid!");
            return $this->redirect(['/referrals/referral']);
        }

        $model = new Attachment();
        if($model->load(Yii::$app->request->post())){
        //if(Yii::$app->request->post()){
            $model->filename = UploadedFile::getInstances($model,'filename');
            //$model->filetype = 1;
            $model->referral_id = $referralId;

            if($model->filename){

                $ch = curl_init();
                $referralCode = $referral->referral_code;
                foreach ($model->filename as $filename) {
                    $file = $referralCode."_".date('YmdHis').".".$filename->extension; //.".".$filename->extension;
                    $file_data = curl_file_create($filename->tempName,$filename->type,$file);

                    $mi = !empty(Yii::$app->user->identity->profile->middleinitial) ? " ".substr(Yii::$app->user->identity->profile->middleinitial, 0, 1).". " : " "; 
                    $uploaderName = Yii::$app->user->identity->profile->firstname.$mi.Yii::$app->user->identity->profile->lastname;

                    $uploader_data = [
                        'file_name' => $referralCode."_".date('YmdHis').".".$filename->extension,
                        'referral_id' => $referralId,
                        'referral_code' => $referralCode,
                        'user_id' => Yii::$app->user->identity->profile->user_id,
                        'uploader' => $uploaderName,
                    ];
                    //$referralUrl='https://eulimsapi.onelab.ph/api/web/referral/attachments/upload_deposit';
                    $referralUrl=$this->apiUrl.'/api/web/referral/attachments/upload_deposit';

                    $data = ['file_data'=>$file_data,'uploader_data'=>json_encode($uploader_data)];

                    //hardcoded curl since the linslin\yii2\curl extension doesn't support create file
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $referralUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                    $response = curl_exec($ch);

                    if($response == 1){
                        Yii::$app->session->setFlash('success', "Deposit slip successfully uploaded.");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                    } elseif($response == 0) {
                        Yii::$app->session->setFlash('error', "Attachment invalid!");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                    } else {
                        Yii::$app->session->setFlash('error', "Can't upload attachment!");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', "Not valid upload attachment!");
                return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
            }
        }
        
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('_formUploadDeposit', [
                'model' => $model,
            ]);
        }
    }

	//upload Official Receipt
    public function actionUpload_or()
    {
        set_time_limit(120);
        if(Yii::$app->request->get('referral_id')){
            $referralId = (int) Yii::$app->request->get('referral_id');
            $referral = $this->findReferral($referralId);
        } else {
            Yii::$app->session->setFlash('error', "Referral ID not valid!");
            return $this->redirect(['/referrals/referral']);
        }

        $model = new Attachment();
        if($model->load(Yii::$app->request->post())){
            $model->filename = UploadedFile::getInstances($model,'filename');
            $model->referral_id = $referralId;
            if($model->filename){
                $ch = curl_init();
                $referralCode = $referral->referral_code;
                foreach ($model->filename as $filename) {
                    $file = $referralCode."_".date('YmdHis').".".$filename->extension;
                    $file_data = curl_file_create($filename->tempName,$filename->type,$file);

                    $mi = !empty(Yii::$app->user->identity->profile->middleinitial) ? " ".substr(Yii::$app->user->identity->profile->middleinitial, 0, 1).". " : " "; 
                    $uploaderName = Yii::$app->user->identity->profile->firstname.$mi.Yii::$app->user->identity->profile->lastname;

                    $uploader_data = [
                        'file_name' => $referralCode."_".date('YmdHis').".".$filename->extension,
                        'referral_id' => $referralId,
                        'referral_code' => $referralCode,
                        'user_id' => Yii::$app->user->identity->profile->user_id,
                        'uploader' => $uploaderName,
                    ];
                    //$referralUrl='https://eulimsapi.onelab.ph/api/web/referral/attachments/upload_or';
                    $referralUrl=$this->apiUrl.'/api/web/referral/attachments/upload_or';

                    $data = ['file_data'=>$file_data,'uploader_data'=>json_encode($uploader_data)];

                    //hardcoded curl since the linslin\yii2\curl extension doesn't support create file
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $referralUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                    $response = curl_exec($ch);

                    if($response == 1){
                        Yii::$app->session->setFlash('success', "Official receipt successfully uploaded.");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                    } elseif($response == 0) {
                        Yii::$app->session->setFlash('error', "Attachment invalid!");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                    } else {
                        Yii::$app->session->setFlash('error', "Can't upload attachment!");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', "Not valid upload attachment!");
                return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
            }
        }
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('_formUploadOR', [
                'model' => $model,
            ]);
        }
    }

	//upload Test Result
    public function actionUpload_result($referralid)
    {
        //echo $referralid;
        //exit;
        if(!empty($referralid)){
            $referralId = (int) $referralid;
            $referral = $this->findReferral($referralId);
        } else {
            Yii::$app->session->setFlash('error', "Referral ID not valid!");
            return $this->redirect(['/referrals/referral']);
        }

        $model = new Attachment();
        if($model->load(Yii::$app->request->post())){
            $model->filename = UploadedFile::getInstances($model,'filename');
            $model->referral_id = $referralId;
            if($model->filename){
                $ch = curl_init();
                $referralCode = $referral->referral_code;
                foreach ($model->filename as $filename) {
                    $file = $referralCode."_".date('YmdHis').".".$filename->extension;
                    $file_data = curl_file_create($filename->tempName,$filename->type,$file);

                    $mi = !empty(Yii::$app->user->identity->profile->middleinitial) ? " ".substr(Yii::$app->user->identity->profile->middleinitial, 0, 1).". " : " "; 
                    $uploaderName = Yii::$app->user->identity->profile->firstname.$mi.Yii::$app->user->identity->profile->lastname;

                    $uploader_data = [
                        'file_name' => $referralCode."_".date('YmdHis').".".$filename->extension,
                        'referral_id' => $referralId,
                        'referral_code' => $referralCode,
                        'user_id' => Yii::$app->user->identity->profile->user_id,
                        'uploader' => $uploaderName,
                    ];
					//$referralUrl='https://eulimsapi.onelab.ph/api/web/referral/attachments/upload_result';
                    $referralUrl=$this->apiUrl.'/api/web/referral/attachments/upload_result';

                    $data = ['file_data'=>$file_data,'uploader_data'=>json_encode($uploader_data)];

                    //hardcoded curl since the linslin\yii2\curl extension doesn't support create file
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, $referralUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                    $response = curl_exec($ch);

                    if($response == 1){
                        $test=ReferralController::Checkstatuslogs($referralId, 6);
                        if($test == 0){
                            $status=ReferralController::Statuslogs($referralId,6); // #6 means Uploaded
                        }
                        Yii::$app->session->setFlash('success', "Test result successfully uploaded.");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                        
                    } elseif($response == 0) {
                        Yii::$app->session->setFlash('error', "Attachment invalid!");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                    } else {
                        Yii::$app->session->setFlash('error', "Can't upload attachment!");
                        return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', "Not valid upload attachment!");
                return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
            }
        }
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('_formUploadResult', [
                'model' => $model,
            ]);
        }
    }

    //for download attachment type deposit slip, official receipt, test result
    public function actionDownload()
    {
        set_time_limit(120);

        if(Yii::$app->request->get('referral_id')){
            $referralId = (int) Yii::$app->request->get('referral_id');
        } else {
            Yii::$app->session->setFlash('error', "Referral request not valid!");
            return $this->redirect(['/referrals/referral']);
        }

        if(Yii::$app->request->get('file')){
            $fileId = (int) Yii::$app->request->get('file');
        } else {
            Yii::$app->session->setFlash('error', "Not a valid file!");
            return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
        }

        if($fileId > 0){
            $function = new ReferralFunctions();

            $referral = $this->findReferral($referralId);
            $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;

            $file_download = $function->downloadAttachment($referral->referral_id,$rstlId,$fileId);

            if($file_download == 'false'){
                Yii::$app->session->setFlash('error', "Can't download file!");
                return $this->redirect(['/referrals/referral/view','id'=>$referralId]);
            } else {
                return $this->redirect($file_download);
            }
        }
    }

    //find referral request
    protected function findReferral($id)
    {
        $model = Referral::find()->where(['referral_id'=>$id])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested Request its either does not exist or you have no permission to view it.');
        }
    }
}
