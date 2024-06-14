<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Meteodata $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="meteodata-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cityId')->textInput() ?>

    <?= $form->field($model, 'lowestTemperature')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
