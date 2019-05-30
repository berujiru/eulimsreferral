<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Referral;

/**
 * ReferralSearch represents the model behind the search form of `common\models\referral\Referral`.
 */
class ReferralSearch extends Referral
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referral_id', 'local_request_id', 'receiving_agency_id', 'testing_agency_id', 'lab_id', 'customer_id', 'payment_type_id', 'modeofrelease_id', 'purpose_id', 'discount_id', 'receiving_user_id', 'testing_user_id', 'bid', 'cancelled'], 'integer'],
            [['referral_code', 'referral_date_time', 'sample_received_date', 'report_due', 'conforme', 'cro_receiving', 'cro_testing', 'created_at_local', 'create_time', 'update_time'], 'safe'],
            [['discount_rate', 'total_fee'], 'number'],
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
        $query = Referral::find()->orderBy('referral_date_time DESC, create_time DESC');

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
            'referral_id' => $this->referral_id,
            'referral_date_time' => $this->referral_date_time,
            'local_request_id' => $this->local_request_id,
            'receiving_agency_id' => $this->receiving_agency_id,
            'testing_agency_id' => $this->testing_agency_id,
            'lab_id' => $this->lab_id,
            'sample_received_date' => $this->sample_received_date,
            'customer_id' => $this->customer_id,
            'payment_type_id' => $this->payment_type_id,
            'modeofrelease_id' => $this->modeofrelease_id,
            'purpose_id' => $this->purpose_id,
            'discount_id' => $this->discount_id,
            'discount_rate' => $this->discount_rate,
            'total_fee' => $this->total_fee,
            'report_due' => $this->report_due,
            'receiving_user_id' => $this->receiving_user_id,
            'testing_user_id' => $this->testing_user_id,
            'bid' => $this->bid,
            'cancelled' => $this->cancelled,
            'created_at_local' => $this->created_at_local,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'referral_code', $this->referral_code])
            ->andFilterWhere(['like', 'conforme', $this->conforme])
            ->andFilterWhere(['like', 'cro_receiving', $this->cro_receiving])
            ->andFilterWhere(['like', 'cro_testing', $this->cro_testing]);

        return $dataProvider;
    }
}
