<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_role".
 *
 * @property integer $ur_id
 * @property string $role_name
 * @property integer $is_active
 * @property integer $is_delete
 * @property integer $level
 * @property integer $app_id
 *
 * @property User[] $users
 */
class UserRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'is_delete', 'level', 'app_id'], 'integer'],
            [['role_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ur_id' => Yii::t('app', 'Ur ID'),
            'role_name' => Yii::t('app', 'Role Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'level' => Yii::t('app', 'Level'),
            'app_id' => Yii::t('app', 'App ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['ur_id' => 'ur_id']);
    }

    /**
     * @inheritdoc
     * @return UserRoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserRoleQuery(get_called_class());
    }
}
