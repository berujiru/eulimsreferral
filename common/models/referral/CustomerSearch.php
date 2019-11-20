<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Customer;

/**
 * CustomerSearch represents the model behind the search form of `common\models\referral\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'rstl_id', 'classification_id', 'barangay_id', 'customer_type_id', 'business_nature_id', 'industrytype_id', 'created_at', 'customer_old_id', 'Oldcolumn_municipalitycity_id', 'Oldcolumn_district', 'local_customer_id'], 'integer'],
            [['customer_code', 'customer_name', 'head', 'address', 'tel', 'fax', 'email'], 'safe'],
            [['latitude', 'longitude'], 'number'],
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
        $query = Customer::find();

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
            'customer_id' => $this->customer_id,
            'rstl_id' => $this->rstl_id,
            'classification_id' => $this->classification_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'barangay_id' => $this->barangay_id,
            'customer_type_id' => $this->customer_type_id,
            'business_nature_id' => $this->business_nature_id,
            'industrytype_id' => $this->industrytype_id,
            'created_at' => $this->created_at,
            'customer_old_id' => $this->customer_old_id,
            'Oldcolumn_municipalitycity_id' => $this->Oldcolumn_municipalitycity_id,
            'Oldcolumn_district' => $this->Oldcolumn_district,
            'local_customer_id' => $this->local_customer_id,
        ]);

        $query->andFilterWhere(['like', 'customer_code', $this->customer_code])
            ->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'head', $this->head])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
