<?php

/** @var yii\web\View $this */

$this->title = 'Tire Alert - ' . Yii::t('app', 'Home');
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">
            <?= Yii::t('app', 'Welcome to Tire Alert') ?>
        </h1>

        <p class="lead">
            <?= Yii::t('app', 'Receive notifications when your tires need to be changed based on temperatures') ?>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2 class="text-center">
                    <?= Yii::t('app', 'Add a City') ?>
                </h2>

                <p class="text-center">
                    <?= Yii::t('app', 'Select the cities you are interested in to receive email notifications when tires need to be changed based on temperatures') ?>
                </p>

                <p class="text-center"><a class="btn btn-outline-secondary" href="/user-city/createbycity">
                        <?= Yii::t('app', 'Add a City') ?>
                    </a></p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2 class="text-center">
                    <?= Yii::t('app', 'My Cities') ?>
                </h2>

                <p class="text-center">
                    <?= Yii::t('app', 'View the cities you have added and the temperature-related information') ?>
                </p>

                <p class="text-center"><a class="btn btn-outline-secondary" href="/user-city/indexbyuser">
                        <?= Yii::t('app', 'View my cities') ?>
                    </a></p>
            </div>
            <div class="col-lg-4">
                <h2 class="text-center">
                    <?= Yii::t('app', 'Account Settings') ?>
                </h2>

                <p class="text-center">
                    <?= Yii::t('app', 'Manage your personal information and modify your account settings') ?>
                </p>

                <p class="text-center"><a class="btn btn-outline-secondary" href="/user/settings/account">
                        <?= Yii::t('app', 'Account Settings') ?>
                    </a></p>
            </div>
        </div>

    </div>
</div>