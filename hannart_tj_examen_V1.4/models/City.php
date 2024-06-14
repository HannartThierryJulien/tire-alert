<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $postcode
 * @property int|null $countryId
 * @property float|null $longitude
 * @property float|null $latitude
 * @property string|null $tireToHaveFitted
 *
 * @property Country $country
 * @property Meteodata[] $meteodatas
 * @property UserCity[] $userCities
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['countryId'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['postcode'], 'string', 'max' => 10],
            [['tireToHaveFitted'], 'string', 'max' => 20],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'postcode' => 'Postcode',
            'countryId' => 'Country ID',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'tireToHaveFitted' => 'Tire To Have Fitted',
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }

    /**
     * Gets query for [[Meteodatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeteodatas()
    {
        return $this->hasMany(Meteodata::class, ['cityId' => 'id']);
    }

    /**
     * Gets query for [[UserCities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCities()
    {
        return $this->hasMany(UserCity::class, ['cityId' => 'id']);
    }
}
