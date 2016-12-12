<?php 

namespace backend\components;

use Yii;
use yii\web\NotFoundHttpException;
use yii\base\Component;

class BaseController extends \yii\web\Controller {
    public function beforeAction($action)
    {
    	return parent::beforeAction($action);	
    	/*if(\Yii::$app->user->can($this->action->id.$action->controller->id)){
        	return parent::beforeAction($action);	
        }else{
        	echo "d";die;
        	//throw new Exception("Error Processing Request", 404);
        }*/
    }
}
?>
