<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Visitedpage;

/**
 * VisitedpageSearch represents the model behind the search form of `common\models\referral\Visitedpage`.
 */
class VisitedpageSearch extends Visitedpage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visited_page_id', 'user_id', 'rstl_id', 'pstc_id'], 'integer'],
            [['home_url', 'module', 'controller', 'action', 'date_visited'], 'safe'],
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
        $query = Visitedpage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'visited_page_id' => $this->visited_page_id,
            'user_id' => $this->user_id,
            'rstl_id' => $this->rstl_id,
            'pstc_id' => $this->pstc_id,
            'date_visited' => $this->date_visited,
        ]);

        $query->andFilterWhere(['like', 'home_url', $this->home_url])
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'controller', $this->controller])
            ->andFilterWhere(['like', 'action', $this->action]);

        return $dataProvider;
    }
}
