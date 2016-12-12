<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "systemsettings".
 *
 * @property integer $sid
 * @property string $name
 * @property string $val
 * @property string $inputfieldtype
 */
class Systemsettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'systemsettings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'val', 'inputfieldtype'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['val'], 'string', 'max' => 255],
            [['inputfieldtype'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sid' => Yii::t('app', 'Sid'),
            'name' => Yii::t('app', 'Name'),
            'val' => Yii::t('app', 'Val'),
            'inputfieldtype' => Yii::t('app', 'Inputfieldtype'),
        ];
    }
}
