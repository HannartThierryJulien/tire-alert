<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "runningprocesses".
 *
 * @property int $id
 * @property int|null $alertingToChangeTyre
 * @property int|null $collectingWeatherData
 */
class Runningprocesses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'runningprocesses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alertingToChangeTyre', 'collectingWeatherData'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alertingToChangeTyre' => 'Alerting To Change Tyre',
            'collectingWeatherData' => 'Collecting Weather Data',
        ];
    }
}
