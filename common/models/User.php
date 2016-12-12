<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use common\helper\AppHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer $user_id
 * @property string $username
 * @property string $password
 * @property integer $ur_id
 * @property integer $ut_id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $user_image
 * @property integer $country_id
 * @property integer $state_id
 * @property integer $city_id
 * @property string $pincode
 * @property string $status
 * @property integer $created_by
 * @property string $created_on
 * @property integer $modified_by
 * @property string $modified_on
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    public $auth_key;
    public $old_password, $new_password, $repeat_password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'password'], 'required'],
            [['ur_id', 'ut_id', 'country_id', 'state_id', 'city_id', 'created_by', 'modified_by'], 'integer'],
            [['username', 'email', 'first_name', 'last_name', 'user_image'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 60],
            [['username'],'customUniqUsername'],
            [['email'],'email'],
            [['email','first_name','last_name','pincode','user_image'],'default','value' => null],

            
            [['pincode'], 'string', 'max' => 20],
            [['status'], 'string'],
            [['created_on', 'modified_on'], 'safe'],
            [['email', 'first_name', 'last_name', 'user_image'], 'string', 'max' => 50],
            [['pincode'], 'string', 'max' => 20],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'city_id']],
            [['ur_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRole::className(), 'targetAttribute' => ['ur_id' => 'ur_id']],
            [['ut_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserType::className(), 'targetAttribute' => ['ut_id' => 'ut_id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['state_id' => 'state_id']],
            ['old_password', 'required', 'on' => 'changepassword'],
            ['new_password', 'required', 'on' => 'changepassword'],
            ['new_password', 'string', 'min' => 8],
            ['new_password', 'match', 'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', 'message' => 'password contains atleast one upercase,one lowecase and special character', 'on' => 'changepassword'],
            ['repeat_password', 'compare', 'compareAttribute' => 'new_password', 'on' => 'changepassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'ur_id' => Yii::t('app', 'Ur ID'),
            'ut_id' => Yii::t('app', 'Ut ID'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'user_image' => Yii::t('app', 'Profile Image'),
            'country_id' => Yii::t('app', 'Country'),
            'state_id' => Yii::t('app', 'State'),
            'city_id' => Yii::t('app', 'City'),
            'pincode' => Yii::t('app', 'Pincode'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Create By'),
            'created_on' => Yii::t('app', 'Create On'),
            'modified_by' => Yii::t('app', 'Modify By'),
            'modified_on' => Yii::t('app', 'Modify On'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity() {
        return $this->hasOne(Cities::className(), ['city_id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUr() {
        return $this->hasOne(UserRole::className(), ['ur_id' => 'ur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUt() {
        return $this->hasOne(UserType::className(), ['ut_id' => 'ut_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry() {
        return $this->hasOne(Countries::className(), ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState() {
        return $this->hasOne(Cities::className(), ['state_id' => 'state_id']);
    }

    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS* */

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    /* modified */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    /* removed
      public static function findIdentityByAccessToken($token)
      {
      throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
      }
     */

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        /* Nisarg Patel 
           12/10/16
           Start  : To check login password with hash security */
        //return $this->password === ($password);
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
        /* End Nisarg Patel */
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Security::generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Security::generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /** EXTENSION MOVIE * */

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find() {
        return new UserQuery(get_called_class());
    }


    public function scenarios() {

        $scenarios = parent::scenarios();
        $scenarios['changepassword'] = ['old_password', 'new_password', 'repeat_password']; //Scenario Values Only Accepted
        return $scenarios;
    }    
    /**
    *  Used for getfull name using concate first_name and last_name
    *  @return result as string 
    */
    public function getFullName($id){
        $result = "";
        $model  = $this->findModel($id);
        if(!empty($model)){
            $result = $model->first_name." ".$model->last_name;
        }
        return $result;
    }

    /**
    *  Used for customuniq validation function for username 
    *  @return result as string or bollean if pass validation
    *  @param $attribute model attribute
    *  @param $params model params with set value
    */
    public function customUniqUsername($attribute, $params){
        if(!empty($this->username)){
            $userName               = strtolower(trim($this->username));
            if($this->user_id>0){
                $result   = $this->find()->where(['LOWER(username)'=>$userName])->andWhere(['<>','user_id',$this->user_id])->count();
            }else{
                $result   = $this->find()->where(['LOWER(username)'=>$userName])->count();
            }
            if($result<=0){
                return true;
            }else{
                return $this->addError($attribute,$this->username .' Is alreday exist!');
            }
        }
    }

    /**
     * @inheritdoc
     * @used For set default dateformate and created by and modified by for every save
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($this->isNewRecord){
                $this->created_by = Yii::$app->user->id;
                $this->created_on = AppHelper::GetDateTime();
            }
            $this->modified_by = Yii::$app->user->id;
            $this->modified_on = AppHelper::GetDateTime();
            return parent::beforeSave($insert);

        } else {
            return false;
        }
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
     * @inheritdoc
     * @used for conver dateformate after find or fetch records
     */
    /*public function afterFind ()
    {
        // convert to display format
        $this->created_on = AppHelper::displayDateTime($this->created_on);
        $this->modified_on = AppHelper::displayDateTime($this->modified_on);

        parent::afterFind ();
    }*/

}
