<?php

namespace app\controllers;

use app\models\UserCity;
use app\models\UserCitySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\City;
use app\models\Country;
use Yii;
use Stichoza\GoogleTranslate\GoogleTranslate;
use app\components\Geocoding;
use app\models\MeteoData;
use yii\filters\AccessControl;
use \app\controllers\BaseController;

/**
 * UserCityController implements the CRUD actions for UserCity model.
 */
class UserCityController extends BaseController
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
                        'actions' => ['indexbyuser', 'createbycity'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserCity models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserCitySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserCity model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserCity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UserCity();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserCity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserCity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserCity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return UserCity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserCity::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     *************************************************************************************************************
     *************************************************************************************************************
     *************************************************************************************************************
     *
     * METHODES PERSONNALISEES
     * 
     *************************************************************************************************************
     *************************************************************************************************************
     *************************************************************************************************************
     */

    /**
     * Version personnalisée de la méthode actionCreate().
     * Permet à un utilisateur de se lier à une ville.
     * Fonctionnement :
     *  1) Vérification si le pays entré existe. Si pas, il est créé dans la DB.
     *  2) Vérification si la ville entrée existe. Si pas, elle est créée dans la DB.
     *  3) Vérification si l'utilisateur est déjà lié à cette ville.
     *     3.1) S'il l'est, on l'en informe via un message.
     *     3.2) S'il ne l'est pas, on créée une nouvelle entrée dans la table user_city
     *          et on lance une collecte de données météo pour cette ville.
     */
    public function actionCreatebycity()
    {
        $model = new UserCity();
        $cityModel = new City();
        $countryModel = new Country();

        if ($this->request->isPost) {
            if ($cityModel->load($this->request->post()) && $countryModel->load($this->request->post())) {

                // Utilisation d'une extension google pour traduire le nom du pays en anglais.
                $translate = new GoogleTranslate();
                $countryNameInEnglish = $translate->setSource('auto')->setTarget('en')->translate($countryModel->name);

                // Vérification si pays enregistré dans DB. Si pas, on le crée.
                $country = Country::findOne(['name' => $countryNameInEnglish]);
                if (!$country) {
                    $country = new Country();
                    $country->name = $countryNameInEnglish;
                    $country->save();
                }

                // Vérification si ville enregistrée dans DB. Si pas, on la crée.
                $city = City::findOne(['name' => $cityModel->name, 'countryId' => $country->id, 'postcode' => $cityModel->postcode]);
                if (!$city) {
                    $city = new City();
                    $city->name = $cityModel->name;
                    $city->countryId = $country->id;
                    $city->postcode = $cityModel->postcode;
                    $coordinates = Geocoding::getCoordinates($city->name . ' ' . $city->postcode . ' ' . $countryNameInEnglish);
                    $city->latitude = $coordinates['latitude'];
                    $city->longitude = $coordinates['longitude'];
                    $city->save();
                }

                // Vérification si un userCity existe déjà pour cette ville et cette personne.
                $existingUserCity = UserCity::findOne(['userId' => Yii::$app->user->id, 'cityId' => $city->id]);
                if ($existingUserCity) {
                    // S'il existe, on affiche un message d'erreur et on redirige.
                    Yii::$app->session->setFlash('error', 'Une entrée UserCity existe déjà pour cette combinaison userId et cityId.');
                    return $this->redirect(['index']); // Rediriger vers la page d'index
                }

                // S'il n'existe pas, on récupère l'id du user connecté et on crée le userCity
                $model->userId = Yii::$app->user->id;
                $model->cityId = $city->id;
                $model->save();

                // On popule la table meteoData pour cette ville d'au moins une température car elle n'en a pas encore.
                Meteodata::updateMeteoData($city->id);

                return $this->redirect(['indexbyuser']);
            }
        }

        return $this->render('createByCity', [
            'model' => $model,
            'cityModel' => $cityModel,
            'countryModel' => $countryModel,
        ]);
    }

    /**
     * Permet d'afficher la page qui contient les user_city de l'utilisateur connecté.
     * C'est donc l'endroit où un utilisateur peut retrouver les villes auxquelles il est lié.
     */
    public function actionIndexbyuser()
    {
        $searchModel = new UserCitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Requête pour sélectionner les userCity liés à l'utilisateur connecté.
        $dataProvider->query->andFilterWhere(['userId' => Yii::$app->user->id]);

        return $this->render('indexByUser', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Version modifiée de la méthode actionDelete().
     * Au lieu de rediriger vers la page "index" qui afficherait tout les userCity de la DB,
     * on redirige l'utilisateur vers la page "indexByUser" qui contient ses userCity uniquement.
     */
    public function actionDeletefromindexbyuser($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['indexbyuser']);
    }
}