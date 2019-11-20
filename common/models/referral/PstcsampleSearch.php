<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Pstcsample;

/**
 * PstcsampleSearch represents the model behind the search form of `common\models\referral\Pstcsample`.
 */
class PstcsampleSearch extends Pstcsample
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pstc_sample_id', 'pstc_request_id', 'rstl_id', 'pstc_id', 'testcategory_id', 'sampletype_id', 'local_sample_id', 'local_request_id', 'is_referral'], 'integer'],
            [['sample_name', 'sample_description', 'sample_code', 'created_at', 'updated_at'], 'safe'],
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
        $query = Pstcsample::find();

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
            'pstc_sample_id' => $this->pstc_sample_id,
            'pstc_request_id' => $this->pstc_request_id,
            'rstl_id' => $this->rstl_id,
            'pstc_id' => $this->pstc_id,
            'testcategory_id' => $this->testcategory_id,
            'sampletype_id' => $this->sampletype_id,
            'local_sample_id' => $this->local_sample_id,
            'local_request_id' => $this->local_request_id,
			'is_referral' => $this->is_referral,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'sample_name', $this->sample_name])
            ->andFilterWhere(['like', 'sample_description', $this->sample_description])
            ->andFilterWhere(['like', 'sample_code', $this->sample_code]);

        return $dataProvider;
    }
}
