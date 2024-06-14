<?php

namespace app\controllers;

use app\models\Runningprocesses;
use yii\filters\AccessControl;
use \app\controllers\BaseController;

/**
 * Permet de gérer les pages et actions découlant du dossier views\settings
 */
class SettingsController extends BaseController
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Permet d'afficher la page settings/index.php
     * Les variables @collectingWeatherData et @alertingToChangeTyre permettent de connaitre l'état actuel des deux processus.
     */
    public function actionIndex()
    {
        $runningProcesses = RunningProcesses::findOne(1);

        return $this->render('index', [
            'collectingWeatherData' => $runningProcesses->collectingWeatherData,
            'alertingToChangeTyre' => $runningProcesses->alertingToChangeTyre,
        ]);
    }


    /**
     * Action to run/stop the Alert process
     */
    /*public function actionAlertprocess()
    {
        return $this->render('index');
    }*/


    /**
     * Permet de changer l'état du processus "collecte de données météo".
     */
    public function actionToggleWeatherDataProcess()
    {
        $runningProcesses = RunningProcesses::findOne(1);

        if ($runningProcesses->collectingWeatherData == 1) {
            $runningProcesses->collectingWeatherData = 0;
        } else {
            $runningProcesses->collectingWeatherData = 1;
        }

        $runningProcesses->save();

        return $this->redirect(['index']);
    }

    /**
     * Permet de changer l'état du processus "Alerter les utilisateurs".
     */
    public function actionToggleAlertToChangeTyreProcess()
    {
        $runningProcesses = RunningProcesses::findOne(1);

        if ($runningProcesses->alertingToChangeTyre == 1) {
            $runningProcesses->alertingToChangeTyre = 0;
        } else {
            $runningProcesses->alertingToChangeTyre = 1;
        }

        $runningProcesses->save();

        return $this->redirect(['index']);
    }

}