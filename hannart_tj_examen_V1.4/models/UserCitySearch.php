<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserCity;
use yii\db\Expression;

/**
 * UserCitySearch represents the model behind the search form of `app\models\UserCity`.
 */
class UserCitySearch extends UserCity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userId', 'cityId'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserCity::find()
            ->joinWith('city')
            ->leftJoin('meteoData', 'meteoData.cityId = city.id')
            ->andWhere([
                'in',
                'meteoData.date',
                [
                    date('Y-m-d'),
                    // Aujourd'hui
                    date('Y-m-d', strtotime('-1 day')),
                    // Hier
                    date('Y-m-d', strtotime('-2 days')) // Avant-hier
                ]
            ])
            ->orderBy(['meteoData.date' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'userId' => $this->userId,
            'cityId' => $this->cityId,
        ]);

        return $dataProvider;
    }
}