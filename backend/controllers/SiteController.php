<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\helper\AppHelper;

use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','requestpasswordreset','resetpassword'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','setclienttimezone','language'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        $this->layout = "simple";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

   
    /*
     * SetClientTimeZone
     * @use For set clienttimezone for whole website using session after successful login called 
     * @params in post : timezonename
     * @return json encoded string with success params
     */
    public function actionSetclienttimezone(){
        $clientTimeZone = AppHelper::param("siteTimeZone");
        $result = array();
        if(!empty($clientTimeZone)){
            \Yii::$app->session->set('clientTimeZone',$clientTimeZone);
        }else{
            if(isset($_POST['timezonename']) && !empty($_POST['timezonename'])){
                $clientTimeZone = $_POST['timezonename'];    
                \Yii::$app->session->set('clientTimeZone',$clientTimeZone);
            }
        }

        if(!empty($clientTimeZone)){
            $result = array('success'=>'success');
        }
        echo json_encode($result);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestpasswordreset()
    {
       /*$this->layout = false;*/
       $this->layout = "simple";
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetpassword($token)
    {
       /*$this->layout = false;*/
       $this->layout = "simple";
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLanguage(){
        
        if(isset($_POST['lang'])){
            //session setting
            $session = Yii::$app->session;
            if (!$session->isActive){
                $session->open();
            }
            Yii::$app->session->set('lang',$_POST['lang']);
           // $session->set('lang',$_POST['lang']);
            if (Yii::$app->session['lang']){
                Yii::$app->language = Yii::$app->session['lang'];
            }
            echo Yii::t('app',"Welcome");
            exit;
        }
    }

}

