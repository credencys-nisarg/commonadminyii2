<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_type".
 *
 * @property integer $ut_id
 * @property string $type_name
 * @property integer $app_id
 *
 * @property User[] $users
 */
class UserType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id'], 'integer'],
            [['type_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ut_id' => Yii::t('app', 'Ut ID'),
            'type_name' => Yii::t('app', 'Type Name'),
            'app_id' => Yii::t('app', 'App ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['ut_id' => 'ut_id']);
    }

    /**
     * @inheritdoc
     * @return UserTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserTypeQuery(get_called_class());
    }
}
