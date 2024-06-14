<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserCity $model */
/** @var app\models\City $cityModel */
/** @var app\models\Country $countryModel */

$this->title = Yii::t('app', 'Create Usercity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Usercities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="usercity-create">
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($countryModel, 'name')->textInput(['maxlength' => true])->label(Yii::t('app', 'Country Name')) ?>

    <?= $form->field($cityModel, 'name')->textInput(['maxlength' => true])->label(Yii::t('app', 'City Name')) ?>

    <?= $form->field($cityModel, 'postcode')->textInput(['maxlength' => true])->label(Yii::t('app', 'City Postal Code')) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>