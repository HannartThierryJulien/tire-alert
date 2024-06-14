<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserCity $model */

$this->title = Yii::t('app', 'Create User City');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-city-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
