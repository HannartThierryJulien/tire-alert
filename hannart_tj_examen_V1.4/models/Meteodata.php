<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meteodata".
 *
 * @property int $id
 * @property int|null $cityId
 * @property float|null $lowestTemperature
 * @property string|null $date
 *
 * @property City $city
 */
class Meteodata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meteodata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cityId'], 'integer'],
            [['lowestTemperature'], 'number'],
            [['date'], 'safe'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['cityId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cityId' => 'City ID',
            'lowestTemperature' => 'Lowest Temperature',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'cityId']);
    }

    /**
     * Permet de créer ou de mettre à jour les données météo concernant une ville spécifique.
     * Si une donnée météo pour une ville donnée et un jour donné est déjà présente, on compare la t° afin de garder la plus basse.
     * But : collecter la donnée t° la plus basse pour chaque journée et pour chaque ville.
     *
     * @param int $cityId
     */
    public static function updateMeteoData($cityId)
    {
        $city = City::findOne($cityId);
        $weatherData = self::getWeatherData($city->latitude, $city->longitude);

        $meteoData = Meteodata::findOne([
            'cityId' => $cityId,
            'date' => date('Y-m-d'),
        ]);

        // Vérification s'il y a déjà une entrée dans la table. S'il n'y en a pas, on la crée.
        if ($meteoData === null) {
            $meteoData = new Meteodata();
            $meteoData->cityId = $cityId;
            $meteoData->lowestTemperature = $weatherData['main']['temp'];
            $meteoData->date = date('Y-m-d');
            $meteoData->save();
        // S'il y en a déjà une, on vérifie si la t° enregistrée est inférieure à la t° trouvée par l'API. On garde la plus basse.
        } else {
            if ($weatherData['main']['temp'] < $meteoData->lowestTemperature) {
                $meteoData->lowestTemperature = $weatherData['main']['temp'];
                $meteoData->save();
            }
        }
    }

    /**
     * Permet de récupérée des données météos complètes pour une latitude et une longitude donnée.
     * La clé de l'APi se trouve dans le fichier config/params.php
     *
     * @param float $latitude
     * @param float $longitude
     * @return array|null
     */
    private static function getWeatherData($latitude, $longitude)
    {
        $apiKey = Yii::$app->params['openWeatherMapAPI'];
        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$apiKey}&units=metric";
        $responseWeather = file_get_contents($weatherUrl);
        $weatherData = json_decode($responseWeather, true);

        return $weatherData;
    }


}