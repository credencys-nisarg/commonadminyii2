<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property integer $city_id
 * @property string $city_name
 * @property integer $state_id
 *
 * @property States $state
 * @property User[] $users
 * @property User[] $users0
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_name', 'state_id'], 'required'],
            [['state_id'], 'integer'],
            [['city_name'], 'string', 'max' => 30],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => States::className(), 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => Yii::t('app', 'City ID'),
            'city_name' => Yii::t('app', 'City Name'),
            'state_id' => Yii::t('app', 'State ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(States::className(), ['state_id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['city_id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers0()
    {
        return $this->hasMany(User::className(), ['state_id' => 'state_id']);
    }

    /**
     * @inheritdoc
     * @return CitiesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CitiesQuery(get_called_class());
    }
}
