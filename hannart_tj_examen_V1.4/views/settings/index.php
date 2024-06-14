<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>
    <?= Html::encode(Yii::t('app', $this->title)) ?>
</h1>

<div class="row justify-content-center">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-header">
                <h5 class="card-title">
                    <?= Yii::t('app', 'Users') ?>
                </h5>
            </div>
            <div class="card-body">
                <?= Html::a(Yii::t('app', 'Manage'), ['/user/admin/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-header">
                <h5 class="card-title">
                    <?= Yii::t('app', 'Countries') ?>
                </h5>
            </div>
            <div class="card-body">
                <?= Html::a(Yii::t('app', 'Manage'), ['/country/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-header">
                <h5 class="card-title">
                    <?= Yii::t('app', 'Meteodatas') ?>
                </h5>
            </div>
            <div class="card-body">
                <?= Html::a(Yii::t('app', 'Manage'), ['/meteodata/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-header">
                <h5 class="card-title">
                    <?= Yii::t('app', 'Cities') ?>
                </h5>
            </div>
            <div class="card-body">
                <?= Html::a(Yii::t('app', 'Manage'), ['/city/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-header">
                <h5 class="card-title">
                    <?= Yii::t('app', 'Cities') . '-' . Yii::t('app', 'Users') ?>
                </h5>
            </div>
            <div class="card-body">
                <?= Html::a(Yii::t('app', 'Manage'), ['/user-city/index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-header">
                <h5 class="card-title">
                    <?= Yii::t('app', 'Weather data collection process') ?>
                </h5>
            </div>
            <div class="card-body">
                <?= Html::a(($collectingWeatherData == 1) ? Yii::t('app', 'Running') : Yii::t('app', 'Stopped'), ['settings/toggle-weather-data-process'], ['class' => 'btn btn-' . (($collectingWeatherData == 1) ? 'success' : 'danger')]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-header">
                <h5 class="card-title">
                    <?= Yii::t('app', 'Mail sending process') ?>
                </h5>
            </div>
            <div class="card-body">
                <?= Html::a(($alertingToChangeTyre == 1) ? Yii::t('app', 'Running') : Yii::t('app', 'Stopped'), ['settings/toggle-alert-to-change-tyre-process'], ['class' => 'btn btn-' . (($alertingToChangeTyre == 1) ? 'success' : 'danger')]) ?>
            </div>
        </div>
    </div>
</div>