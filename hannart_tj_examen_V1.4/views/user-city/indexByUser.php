<?php

use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserCitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'User Cities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-city-index">

    <h1>
        <?= $this->title ?>
    </h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'city.name',
                'label' => Yii::t('app', 'City Name'),
            ],
            [
                'attribute' => 'city.tireToHaveFitted',
                'label' => Yii::t('app', 'Tires to Have Fitted'),
                'value' => function ($model) {
                        if (isset($model->city->tireToHaveFitted)) {
                            return Yii::t('app', $model->city->tireToHaveFitted);
                        }
                        return Yii::t('app', 'Requires 3 days of weather history');
                    },
            ],

            [
                'label' => Yii::t('app', 'Lowest temp. today'),
                'value' => function ($model) {
                        $meteoData = $model->city->meteodatas;
                        $lowestTemp = Yii::t('app', 'No data collected');
                        foreach ($meteoData as $data) {
                            if ($data->date === date('Y-m-d')) {
                                $lowestTemp = $data->lowestTemperature . '°C';
                                break;
                            }
                        }
                        return $lowestTemp;
                    },
            ],

            [
                'label' => Yii::t('app', 'Lowest temp. yesterday'),
                'value' => function ($model) {
                        $meteoData = $model->city->meteodatas;
                        $lowestTemp = Yii::t('app', 'No data collected');
                        foreach ($meteoData as $data) {
                            if ($data->date === date('Y-m-d', strtotime('-1 day'))) {
                                $lowestTemp = $data->lowestTemperature . '°C';
                                break;
                            }
                        }
                        return $lowestTemp;
                    },
            ],

            [
                'label' => Yii::t('app', 'Lowest temp. day before'),
                'value' => function ($model) {
                        $meteoData = $model->city->meteodatas;
                        $lowestTemp = Yii::t('app', 'No data collected');
                        foreach ($meteoData as $data) {
                            if ($data->date === date('Y-m-d', strtotime('-2 days'))) {
                                $lowestTemp = $data->lowestTemperature . '°C';
                                break;
                            }
                        }
                        return $lowestTemp;
                    },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'header' => Yii::t('app', 'Delete'),
                'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'delete') {
                            return Url::to(['/user-city/deletefromindexbyuser', 'id' => $model->id]);
                        }
                    },
            ],

        ],
    ]); ?>

</div>