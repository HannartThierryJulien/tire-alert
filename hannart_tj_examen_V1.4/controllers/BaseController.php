<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * Classe permettant le changement de langue en direct.
 */
class BaseController extends Controller
{
    /**
     * Cette méthode est exécutée avant l'action du contrôleur.
     * Elle vérifie si la langue est définie dans la session de l'application Yii.
     * Si c'est le cas, elle met à jour la langue de l'application Yii en fonction de la valeur stockée dans la session.
     * @param $action l'action à exécuter
     * @return bool true pour exécuter l'action, false pour annuler l'exécution de l'action
     */
    public function beforeAction($action)
    {
        if (isset(Yii::$app->session['language'])) {
            Yii::$app->language = Yii::$app->session['language'];
        }
        return parent::beforeAction($action);
    }

    /**
     * Cette action est appelée depuis views\layouts\main.php pour changer la langue de l'application Yii.
     * Elle reçoit la nouvelle langue en tant que paramètre et met à jour la langue de l'application Yii.
     * Ensuite, elle redirige l'utilisateur vers la page précédente ou la page d'accueil si la page précédente n'est pas disponible.
     * @param $language la nouvelle langue à définir
     * @return \yii\web\Response la réponse de redirection
     */
    public function actionChangeLanguage($language)
    {
        Yii::$app->session->set('language', $language);
        Yii::$app->language = $language;
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}
