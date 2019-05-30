<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Testbid;

/**
 * TestbidSearch represents the model behind the search form of `common\models\referral\Testbid`.
 */
class TestbidSearch extends Testbid
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['analysis_bid_id', 'bidder_agency_id', 'referral_id', 'bid_id', 'analysis_id'], 'integer'],
            [['fee'], 'number'],
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
        $query = Testbid::find();

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
            'analysis_bid_id' => $this->analysis_bid_id,
            'bidder_agency_id' => $this->bidder_agency_id,
            'referral_id' => $this->referral_id,
            'bid_id' => $this->bid_id,
            'analysis_id' => $this->analysis_id,
            'fee' => $this->fee,
        ]);

        return $dataProvider;
    }
}
