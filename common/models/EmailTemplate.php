<?php

namespace common\models;

use Yii;
use common\helper\AppHelper;

/**
 * This is the model class for table "email_template".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $content
 * @property integer $created_by
 * @property string $created_on
 * @property integer $modified_by
 * @property string $modified_on
 */
class EmailTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['content'], 'string'],
            [['code'],'customUniqCode'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_on', 'modified_on'], 'safe'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'content' => Yii::t('app', 'Content'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_on' => Yii::t('app', 'Created On'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'modified_on' => Yii::t('app', 'Modified On'),
        ];
    }

    /**
    *  Used for customuniq validation function for username 
    *  @return result as string or bollean if pass validation
    *  @param $attribute model attribute
    *  @param $params model params with set value
    */
    public function customUniqCode($attribute, $params){
        if(!empty($this->code)){
            $code               = strtolower(trim($this->code));
            if($this->id>0){
                $result   = $this->find()->where(['LOWER(code)'=>$code])->andWhere(['<>','id',$this->id])->count();
            }else{
                $result   = $this->find()->where(['LOWER(code)'=>$code])->count();
            }
            if($result<=0){
                return true;
            }else{
                return $this->addError($attribute,$this->code .' Is alreday exist!');
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

    /**
     * @inheritdoc
     * @used for conver dateformate after find or fetch records
     */
    /*public function afterrFind ()
    {
        // convert to display format
        $this->created_on = AppHelper::displayDateTime($this->created_on);
        $this->modified_on = AppHelper::displayDateTime($this->modified_on);

        parent::afterFind ();
    }*/

    /**
     * @used for get mailcontent with replace dynamic data
     * @param $code string uniq code or template identifier
     * @param $replaceArry array with email data key as find in template value as data to be replaced in template
     * @return content as string
     */
    public function getMailContent($code,$replaceArry=array()){
        $result = "";
        if(!empty($code)){
            $mailTemplate = $this->find()->select(['id', 'content'])->where(['code' => $code])->one();
            if(!empty($mailTemplate)){
                $content = $mailTemplate->content;
                if(!empty($replaceArry)){
                    foreach ($replaceArry as $key => $value) {
                        $content = str_replace($key,$value,$content);
                    }
                }
                $result = $content;
            }
        }
        return $result;
    }
}
