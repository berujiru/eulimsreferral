<?php

namespace frontend\controllers;

use Yii;
use common\models\referral\Visitedpage;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VisitedpageController implements the CRUD actions for Visitedpage model.
 */
class VisitedpageController extends Controller
{
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

    public function actionLogpage()
    {
        $rstlId = (int) Yii::$app->user->identity->profile->rstl_id;


        if($rstlId > 0) {
            $request = Yii::$app->request;
            $pstcId = !empty(Yii::$app->user->identity->profile->pstc_id) ? (int) Yii::$app->user->identity->profile->pstc_id : '';

            $model = new Visitedpage();            
            $model->absolute_url = $request->absoluteUrl;
            $model->home_url = $request->hostInfo;
            $model->module =  Yii::$app->controller->module;
            $model->controller = Yii::$app->controller->id;
            $model->action = Yii::$app->controller->action->id;
            $model->user_id = (int) Yii::$app->user->identity->profile->user_id;
            $model->rstl_id = $rstlId;
            $model->pstc_id = $pstcId;
            $model->date_visited = date('Y-m-d H:i:s');
            $model->save();
        } else {
            return $this->redirect(['/site/login']);
        }
    }
}
