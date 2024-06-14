<?php

namespace app\commands;

use app\components\MailHog;

use yii\console\Controller;
use app\models\City;
use app\models\MeteoData;
use app\models\RunningProcesses;
use Da\User\Model\User;

/**
 * Classe permettant d'alerter les utilisateurs dont la variable tireToHaveFitted de la ville a changé de status.
 * Pour la lancer, il faut utiliser la commande "yii temperature-alert/index".
 */
class TemperatureAlertController extends Controller
{
    public function actionIndex()
    {
        while (true) {
            // Récupération de l'entrée qui permet de savoir si le processus est en cours.
            $runningProcess = RunningProcesses::findOne(1);

            if ($runningProcess->alertingToChangeTyre) {
                $cities = City::find()->all();

                // Si le processus est indiqué comme "en cours d'exécution" par la DB, on vérifie s'il faut contacter les utilisateurs.
                foreach ($cities as $city) {
                    // Requête pour connaitre le nombre de jours <= à 7°C pour chaque ville lors des 3 derniers jours.
                    $lowestTemperatureCount = MeteoData::find()
                        ->andWhere(['cityId' => $city->id])
                        ->andWhere(['>=', 'date', date('Y-m-d', strtotime('-3 days'))])
                        ->andWhere(['<=', 'lowestTemperature', 7])
                        ->count();

                    // Requête pour connaître le nombre de jours > 7°C pour chaque ville lors des 3 derniers jours.
                    $higherTemperatureCount = MeteoData::find()
                        ->andWhere(['cityId' => $city->id])
                        ->andWhere(['>=', 'date', date('Y-m-d', strtotime('-3 days'))])
                        ->andWhere(['>', 'lowestTemperature', 7])
                        ->count();

                    // S'il y a 3 jours ou plus où il faisait <= 7°C, alors les pneus hiver doivent être mis.
                    if ($lowestTemperatureCount >= 3) {
                        $newTireToHaveFitted = 'winter';
                    // S'il y a 3 jours ou plus où il faisait > 7°C, alors les pneus été doivent être mis.
                    } elseif ($higherTemperatureCount >= 3) {
                        $newTireToHaveFitted = 'summer';
                    // Sinon, laisse la variable @tireToHaveFitted dans l'état où elle est.
                    } else {
                        $newTireToHaveFitted = null;
                    }

                    // On vérifie quel est l'état de la variable tireToHaveFitted de la ville.
                    // Si, la variable est null, c'est parce qu'on n'a pas trouvé de résultat concret pour les 3 derniers jours,
                    // on laisse donc la variable tireToHaveFitted en DB dans l'était où elle est.
                    if ($newTireToHaveFitted == null) {
                        break;

                    // S'il est différent du résultat qu'on a trouvé, alors on prévient tout les utilisateurs liés à cette ville.
                    } elseif ($city->tireToHaveFitted !== $newTireToHaveFitted) {
                        $userEmails = User::find()
                            ->select('email')
                            ->innerJoin('user_city', 'user.id = user_city.userId')
                            ->andWhere(['user_city.cityId' => $city->id])
                            ->column();

                        $this->sendEmailAlert($userEmails, $city, $newTireToHaveFitted);
                        $city->tireToHaveFitted = $newTireToHaveFitted;
                        $city->save();

                        echo "\n\n====> Envoi de mails le " . date('Y-m-d H:i:s') . " <====\n\n";
                    }
                }
            }

            //Pause dans le processus pour ne pas spammer les utilisateurs
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

    /**
     * Fonction permettant de configurer le sujet et le contenu du mail.
     * Ensuite, utilisation de la classe MailHog.php pour envoyer un mail à chaque utilisateur de la liste @userEmails.
     */
    private function sendEmailAlert($userEmails, $city, $newTireToHaveFitted)
    {
        $subject = 'Alert : Tire change needed';
        $message = "Weather conditions in " . $city->name . " have changed.
                    \nPlease consider switching to tires '" . $newTireToHaveFitted . "'.";

        foreach ($userEmails as $userEmail) {
            MailHog::sendEmail("tireChange@alert.com", $userEmail, $subject, $message);
        }
    }
}