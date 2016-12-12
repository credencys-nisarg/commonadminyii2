<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "countries".
 *
 * @property integer $country_id
 * @property string $country_code
 * @property string $country_name
 *
 * @property States[] $states
 * @property User[] $users
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code', 'country_name'], 'required'],
            [['country_code'], 'string', 'max' => 3],
            [['country_name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => Yii::t('app', 'Country ID'),
            'country_code' => Yii::t('app', 'Country Code'),
            'country_name' => Yii::t('app', 'Country Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasMany(States::className(), ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['country_id' => 'country_id']);
    }

    /**
     * @inheritdoc
     * @return CountriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountriesQuery(get_called_class());
    }
}
