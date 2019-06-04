<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Bidnotification;

/**
 * BidnotificationSearch represents the model behind the search form of `common\models\referral\Bidnotification`.
 */
class BidnotificationSearch extends Bidnotification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_notification_id', 'referral_id', 'postedby_agency_id', 'recipient_agency_id', 'seen'], 'integer'],
            [['posted_at', 'seen_date'], 'safe'],
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
        $query = Bidnotification::find();

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
            'bid_notification_id' => $this->bid_notification_id,
            'referral_id' => $this->referral_id,
            'postedby_agency_id' => $this->postedby_agency_id,
            'posted_at' => $this->posted_at,
            'recipient_agency_id' => $this->recipient_agency_id,
            'seen' => $this->seen,
            'seen_date' => $this->seen_date,
        ]);

        return $dataProvider;
    }
}
