<?php

namespace app\commands;

use yii\console\Controller;
use app\models\City;
use app\models\MeteoData;
use app\models\RunningProcesses;

/**
 * Classe permettant le processus de collecte de données météo.
 * Pour la lancer, il faut utiliser la commande "yii weather-update/index".
 * La collecte s'éffectue grâce à la méthode updateMeteoData().
 */
class WeatherUpdateController extends Controller
{
    public function actionIndex()
    {
        while (true) {
            // Récupération de l'entrée qui permet de savoir si le processus est en cours.
            $runningProcess = RunningProcesses::findOne(1);

            // Si le processus est indiqué comme "en cours d'exécution" par la DB, la collecte de données est lancée.
            if ($runningProcess->collectingWeatherData) {
                $cities = City::find()->all();

                foreach ($cities as $city) {
                    Meteodata::updateMeteoData($city->id);
                }
                echo "\n\n====> Données météo collectées le " . date('Y-m-d H:i:s') . " <====\n\n";
            }

            //Pause dans le processus pour ne pas collecter intempestivement des données 
            $remainingSeconds = 10;
            while ($remainingSeconds > 0) {
                $hours = floor($remainingSeconds / 3600);
                $minutes = floor(($remainingSeconds % 3600) / 60);
                $seconds = $remainingSeconds % 60;

                $formattedTime = sprintf("Pause de : %02d:%02d:%02d", $hours, $minutes, $seconds);

                echo "\r" . $formattedTime;
                sleep(1);
                $remainingSeconds--;
            }
        }
    }
}