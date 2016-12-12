<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_log".
 *
 * @property integer $ul_id
 * @property integer $user_id
 * @property string $Description
 * @property string $login_time
 * @property string $logout_time
 *
 * @property User $user
 */
class UserLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['Description', 'login_time', 'logout_time'], 'required'],
            [['login_time', 'logout_time'], 'safe'],
            [['Description'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ul_id' => Yii::t('app', 'Ul ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'Description' => Yii::t('app', 'Description'),
            'login_time' => Yii::t('app', 'Login Time'),
            'logout_time' => Yii::t('app', 'Logout Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }
    /* get Users activity */
   /*public function getUserActivity($id){
       
        if(!empty($id)){
                $result = User::find()
                   ->where('userid > :userid', [':userid' => $userid])
                   ->all();
            }
            if(!empty($result)){
                return $result;
            }else{
               return false;
            }
        }*/
}
