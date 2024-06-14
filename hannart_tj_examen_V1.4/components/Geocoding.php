<?php

namespace app\components;

use Yii;

/**
 * Classe permettant de récolter la latitude et la longitude d'une adresse passée en paramètre.
 * Elle fonctionne avec l'api Geocode de google.
 * La clé de l'api est stockée dans le fichier config\params.php
 */
class Geocoding
{
    public static function getCoordinates($address)
    {
        $apiKey = Yii::$app->params['googleGeocodeAPI'];
        $address = urlencode($address);
        $geocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

        $responseGeocoding = file_get_contents($geocodingUrl);
        $geocodingData = json_decode($responseGeocoding, true);

        if ($geocodingData['status'] === 'OK' && !empty($geocodingData['results'])) {
            $latitude = $geocodingData['results'][0]['geometry']['location']['lat'];
            $longitude = $geocodingData['results'][0]['geometry']['location']['lng'];

            return [
                'latitude' => $latitude,
                'longitude' => $longitude
            ];
        }

        return null;
    }
}