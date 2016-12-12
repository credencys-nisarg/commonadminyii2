<?php
/**
 * Created by PhpStorm.
 * User: vaishakhi
 * Date: 24/10/16
 * Time: 5:13 PM
 * It will check before every request that language is set in session 
 */

namespace backend\components;


class CheckLanguage extends \yii\base\Behavior
{
    public function events(){
        return [
            \yii\web\Application::EVENT_BEFORE_REQUEST => 'changeLanguage',
        ];
    }

    public function changeLanguage(){
//        if(\Yii::$app->getRequest()->getCookies()->has('lang')){
//            \Yii::$app->language = \Yii::$app->getRequest()->getCookies()->getValue('lang');
//        }
        if (\Yii::$app->session['lang']){
            \Yii::$app->language = \Yii::$app->session['lang'];
        }
    }

}