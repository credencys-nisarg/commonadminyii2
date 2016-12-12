<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use api\modules\v1\models\User;
use yii\helpers\Security;
use yii\base\Model;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use Firebase\JWT\JWT;

/**
 * User Controller API
 *
 * @author Nisarg Patel <nisarg.patel@credencys.com>
 */
/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="localhost",
 *     basePath="/commonadminyii2/api/web/v1",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Yii2 common apis for user module",
 *         description="Yii2 Common admin apis for user module to implement functionalities like list all users, create a new user, details of the user, update the user and delete the user. 
 *                      JWT Bearer authentication is used for header authentication purpose. So in header of each api, key will be 'authorization' and value will be 'Bearer auth_key'.
 *                      At time of login, auth_key will be retrieved and can be used as JWT authentication for other web services.  
 *                      Create and login apis don't require JWT authentication.",
 *         @SWG\Contact(
 *             email="nisarg.patel@credencys.com"
 *         ), 
 *     ),
 * )
 */
/**
 * @SWG\Tag(
 *   name="user",
 *   description="Everything about users",
 * )
 */
class UserController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\User';
    use \msheng\JWT\UserTrait;
 	public function behaviors()
    {
    	$actions = parent::actions();
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [HttpBearerAuth::className(),],
            'except' => ['create','login'],
        ];
        return $behaviors;
    }
  
     /**
     * @SWG\Get(
     *     path="/users",
     *     tags={"user"},
     *     description="Returns all users.",
     *     operationId="users",
     *     produces={"application/json", "application/xml"}, 
     *     @SWG\Parameter(
     *         name="authorization",
     *         in="header",
     *         description="Provided auth key (use 'Bearer  ' before auth_key)",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *     ),   
     *     @SWG\Response(
     *         response=200,
     *         description="user response",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/users")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Your request was made with invalid credentials.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     )
     * )
     */

      /**
     * @SWG\Get(
     *     path="/users/{id}",
     *     tags={"user"},
     *     description="To fetch information about any specific user",
     *     operationId="users",
     *     produces={"application/json", "application/xml"}, 
     *     @SWG\Parameter(
     *         name="authorization",
     *         in="header",
     *         description="Provided auth key (use 'Bearer ' before auth_key)",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ), 
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="User Id",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),  
     *     @SWG\Response(
     *         response=200,
     *         description="user response",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/users")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Your request was made with invalid credentials.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     )
     * )
     */

      /**
     * @SWG\Post(
     *     path="/users",
     *     tags={"user"},
     *     description="Allow to create or register new user",
     *     operationId="users (method post)",
     *     produces={"application/json", "application/xml"},
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username to logged in",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password to logged in",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="first_name",
     *         in="formData",
     *         description="User's first name  ",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="last_name",
     *         in="formData",
     *         description="User's last name",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ), 
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="User's email id",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="ur_id",
     *         in="formData",
     *         description="User's role id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),
     *     @SWG\Parameter(
     *         name="ut_id",
     *         in="formData",
     *         description="User's type id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),
     *     @SWG\Parameter(
     *         name="user_image",
     *         in="formData",
     *         description="User's profile image",
     *         required=false,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="country_id",
     *         in="formData",
     *         description="User's country id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),
     *     @SWG\Parameter(
     *         name="state_id",
     *         in="formData",
     *         description="User's state id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),    
     *     @SWG\Parameter(
     *         name="city_id",
     *         in="formData",
     *         description="User's city id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),
     *     @SWG\Parameter(
     *         name="pincode",
     *         in="formData",
     *         description="User's pincode",
     *         required=false,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
      *     @SWG\Parameter(
     *         name="status",
     *         in="formData",
     *         description="User's status",
     *         required=false,
     *         type="string",
     *         enum = "01",
     *         @SWG\Items(type="enum"),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Created and user details will be responded",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/users")
     *         ),
     *     ),    
     *     @SWG\Response(
     *         response="422",
     *         description="Data Validation Failed.",
     *         @SWG\Schema(
     *             type="json"     
     *         )
     *     )
     * )
     */

     /**
     * @SWG\Post(
     *     path="/users/login",
     *     tags={"user"},
     *     description="Returns status for either login is success or not.",
     *     operationId="users/login",
     *     produces={"application/json", "application/xml"},     
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username to logged in",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Correct password to logged in",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),   
     *     @SWG\Response(
     *         response=200,
     *         description="respond as either login is success or not",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/users/login")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Your request was made with invalid credentials.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="If any internal error occurs, responed message key will help to handle this error.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     )
     * )
     */
    public function actionLogin()
    {
        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $identity = User::findOne(['username' => $username]);                   
            if(count($identity) > 0)
            {
                if (Yii::$app->getSecurity()->validatePassword($password, $identity->password)) {               
                    $identity->auth_key = $identity->getJwt();  
                    $identity->save();
                    return array("status"=>1, "message"=>"Login is successful.", "details"=>$identity);
                } else {                
                    return array("status"=>0, "message"=>"Invalid Password.");
                }   
            }
            else
            {
                return array("status"=>0, "message"=>"User not found.");
            }
        }
        else
        {
            return array("status"=>0, "message"=>"Username or password field is missing.");
        }
    }
    /**
     * @SWG\Post(
     *     path="/users/change-password",
     *     tags={"user"},
     *     description="To change user password",
     *     operationId="users/changePassword",
     *     produces={"application/json", "application/xml"}, 
     *     @SWG\Parameter(
     *         name="authorization",
     *         in="header",
     *         description="Provided auth key (use 'Bearer ' before auth_key)",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username to change password",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="new_password",
     *         in="formData",
     *         description="New password to set for user",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="old_password",
     *         in="formData",
     *         description="Old password to verify with current user",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ), 
     *     @SWG\Response(
     *         response=200,
     *         description="status 1 if password changed successfully or status 0 - reason mentioned in message.",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/users/ChangePassword")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Your request was made with invalid credentials.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="If any internal error occurs, responed message key will help to handle this error.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     )
     * )
     */
    public function actionChangePassword()
    {
    	if( isset($_POST['username']) && (isset($_POST['old_password'])) && (isset($_POST['new_password'])) )
    	{
			$username = $_POST['username'];
			$password = $_POST['old_password'];
			$newPassword = $_POST['new_password'];
    		$user = User::findOne(['username' => $username]);
    		if(count($user) > 0)
    		{
	    		if (Yii::$app->getSecurity()->validatePassword($password, $user->password)) {
                    $newPassword = \Yii::$app->security->generatePasswordHash($newPassword);
	    			$user->password = $newPassword;
	    			$user->save();
	    			return array("status"=>1, "message"=>"Password changed successfully.", "details"=>$user);
				} else {
	    			return array("status"=>0, "message"=>"Old password does not match.");
				}
			}
    		else
    		{
    			return array("status"=>0, "message"=>"User not found.");
    		}
    	}
        else
        {
            return array("status"=>0, "message"=>"username, old_password or new_password field is missing.");
        }
    }
    /**
     * @SWG\Post(
     *     path="/users/forgot-password",
     *     tags={"user"},
     *     description="To send email for forgot password.",
     *     operationId="users/forgotPassword",
     *     produces={"application/json", "application/xml"}, 
     *     @SWG\Parameter(
     *         name="authorization",
     *         in="header",
     *         description="Provided auth key (use 'Bearer ' before auth_key)",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username to get password reset link",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),     
     *     @SWG\Response(
     *         response=200,
     *         description="Status 1 if password changed successfully or status 0 - reason mentioned in message.",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/users/ChangePassword")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Your request was made with invalid credentials.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="If any internal error occurs, responed message key will help to handle this error.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     )
     * )
     */
    public function actionForgotPassword()
    {
    	if(isset($_POST['username']))
    	{
			$username = $_POST['username'];			
            $user = User::findOne(['username' => $username]);            
    		if(count($user) > 0)
    		{
                if (!User::isPasswordResetTokenValid($user->password_reset_token)) {                 
                     $user->generatePasswordResetToken();
                    if (!$user->save(false)) {                            
                        return false;
                    }
                }                
                /* Start Send Email */
    			$responseSendMail = Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($user->email)
                ->setSubject('Password reset for ' . Yii::$app->name)
                ->send();
                
                /* End Send Email */
                if($responseSendMail == 1)
                {                    
                    return array("status"=>1, "message"=>"Email sent.");
                }
                else
                {
                    return array("status"=>0, "message"=>"Error sending email.");                    
                }
	    		
			}
    		else
    		{
    			return array("status"=>0, "message"=>"User not found.");
    		}
    	}
        else
        {            
            return array("status"=>0, "message"=>"username field is missing.");
        }
    }
     /**
     * @SWG\Put(
     *     path="/users/{id}",
     *     tags={"user"},
     *     description="Allow to update registered user",
     *     operationId="users (method put)",
     *     produces={"application/json", "application/xml"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="User Id",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),  
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="Username to logged in",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password to logged in",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="first_name",
     *         in="formData",
     *         description="User's first name  ",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="last_name",
     *         in="formData",
     *         description="User's last name",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ), 
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="User's email id",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="ur_id",
     *         in="formData",
     *         description="User's role id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),
     *     @SWG\Parameter(
     *         name="ut_id",
     *         in="formData",
     *         description="User's type id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),
     *     @SWG\Parameter(
     *         name="user_image",
     *         in="formData",
     *         description="User's profile image",
     *         required=false,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Parameter(
     *         name="country_id",
     *         in="formData",
     *         description="User's country id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),
     *     @SWG\Parameter(
     *         name="state_id",
     *         in="formData",
     *         description="User's state id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),    
     *     @SWG\Parameter(
     *         name="city_id",
     *         in="formData",
     *         description="User's city id",
     *         required=false,
     *         type="integer",
     *         @SWG\Items(type="integer"),     
     *     ),
     *     @SWG\Parameter(
     *         name="pincode",
     *         in="formData",
     *         description="User's pincode",
     *         required=false,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
      *     @SWG\Parameter(
     *         name="status",
     *         in="formData",
     *         description="User's status",
     *         required=false,
     *         type="string",
     *         enum = "01",
     *         @SWG\Items(type="enum"),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Updated and user details will be responded",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/users")
     *         ),
     *     ),    
     *     @SWG\Response(
     *         response="422",
     *         description="Data Validation Failed.",
     *         @SWG\Schema(
     *             type="json"     
     *         )
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Your request was made with invalid credentials.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="If any internal error occurs, responed message key will help to handle this error.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     )
     * )
     */

     /**
     * @SWG\Delete(
     *     path="/users/{id}",
     *     tags={"user"},
     *     description="Allow to delete registered user",
     *     operationId="users (method delete)",
     *     produces={"application/json", "application/xml"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="User Id",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),     
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="No Content",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/users")
     *         ),
     *     ),    
     *     @SWG\Response(
     *         response="422",
     *         description="Data Validation Failed.",
     *         @SWG\Schema(
     *             type="json"     
     *         )
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Your request was made with invalid credentials.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="If any internal error occurs, responed message key will help to handle this error.",
     *         @SWG\Schema(
     *             type="json",
     *             ref="#\yii\\web\\UnauthorizedHttpException"
     *         )
     *     )
     * )
     */
}