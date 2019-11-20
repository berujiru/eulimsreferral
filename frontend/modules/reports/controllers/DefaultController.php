<?php 

namespace frontend\modules\reports\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `Lab` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	if(Yii::$app->user->can("super-administrator")){
    		//super-admin
    		return $this->render('index_admin');
       //} elseif (Yii::$app->user->can("CRO")) {
        //	return $this->render('index_non_admin');
        } else {
        	//return $this->render('index_non_admin');
        	//return $this->redirect(['/referrals/referral']);
        	return $this->redirect(['/reports/accomplishment']);
        }
        //return $this->render('index');
    }
}