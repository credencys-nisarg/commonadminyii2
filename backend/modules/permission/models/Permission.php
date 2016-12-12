<?php

namespace backend\modules\permission\models;

use Yii;

/**
 * This is the model class for table "permission".
 *
 * @property integer $permission_id
 * @property integer $roleid
 * @property integer $userid
 * @property string $action_name
 * @property string $class_name
 */
class Permission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roleid', 'userid'], 'required'],
            [['roleid', 'userid'], 'integer'],
            [['action_name'], 'string'],
            [['class_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permission_id' => 'Permission ID',
            'roleid' => 'User Role',
            'userid' => 'User',
            'action_name' => 'Action Name',
            'class_name' => 'Class Name',
        ];
    }
}
