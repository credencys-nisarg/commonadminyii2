<?php

namespace common\components;

use Yii;
use yii\base\Component;
use backend\modules\permission\models\Permission;

/*
 * My component implements the basic common helper functions
 */
class MyComponent extends Component
{
    /**
     * here you can declare usable object & vars
     */
    
    /**
     * Function to set session value
     * @param string $key Session variable name
     * @param string $val Session variable value
     */
    public function setSession($key, $val){
        $session = Yii::$app->session;
        $session->set($key, $val);
    }
    /**
     * Function to get session value
     * @param string $key Session variable name
     * @return boolean
     */
    public function getSession($key) {
        $session = Yii::$app->session;
        if ($session->has($key)) {
            return $session->get($key);
        } else {
            return false;
        }
    }
    
    /**
     * Function to get user action permission of class
     * @return array Actions
     */
    public function getPermission(){
        // Call to get session value
        $userId = $this->getSession('adminid');
        $currentController = strtolower(Yii::$app->controller->id);
        // Get user permission of class
        $methods = Permission::find()->where(['userid' => $userId,'class_name' => $currentController])->one();
        return explode(",", $methods->action_name);
    }
}

