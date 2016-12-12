<?php

namespace api\modules\v1\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\helper\AppHelper;

use Firebase\JWT\JWT;
use yii\web\Request as WebRequest;
/**
 * User Model
 *
 * @author Nisarg Patel <nisarg.patel@credencys.com>
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
    *  For JWT token generation
    */
    use \msheng\JWT\UserTrait;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['user_id'];
    }
   
    /**
     * @param array $payload
     * @return string encoded JWT
     */
    public function getJWT($payload = [])
    {        
        $secret = static::getSecretKey();
        $currentTime = time();
        $request = Yii::$app->request;
        $hostInfo = '';

        // There is also a \yii\console\Request that doesn't have this property
        if ($request instanceof WebRequest) {
            $hostInfo = $request->hostInfo;
        }
        $payload['iss'] = $hostInfo;
        $payload['aud'] = $hostInfo;
        $payload['iat'] = $currentTime;
        $payload['nbf'] = $currentTime;

        // Set up user id
        $payload['uid'] = $this->getPayloadUid();         
        if (!isset($payload['exp'])) {
            $payload['exp'] = $currentTime + static::getJwtExpire();            
        }        
        return JWT::encode($payload, $secret, static::getAlgo());
    }
    /**
     * Define rules for validation
     */
    public function rules()
    {
        return [
            [['username','first_name','last_name','email','password'], 'required']
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
  
    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
         return $timestamp + $expire >= time();
    }
    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                //$this->auth_key = \Yii::$app->security->generateRandomString();
                $this->auth_key = $this->getJwt();
                $this->created_by = Yii::$app->user->id;
                $this->created_on = AppHelper::GetDateTime();
                $this->password = \Yii::$app->security->generatePasswordHash($this->password);
            }            
            $this->modified_by = Yii::$app->user->id;
            $this->modified_on = AppHelper::GetDateTime();
            return true;
        }
        return false;
    }
}
