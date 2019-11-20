<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Pstcanalysis;

/**
 * PstcanalysisSearch represents the model behind the search form of `common\models\referral\Pstcanalysis`.
 */
class PstcanalysisSearch extends Pstcanalysis
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pstc_analysis_id', 'rstl_id', 'pstc_id', 'testname_id', 'method_id', 'quantity', 'is_package', 'is_package_name', 'local_analysis_id', 'local_sample_id', 'cancelled','analysis_offered'], 'integer'],
            [['pstc_sample_id', 'testname', 'method', 'reference', 'created_at', 'updated_at'], 'safe'],
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
        $query = Pstcanalysis::find();

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
            'pstc_analysis_id' => $this->pstc_analysis_id,
            'rstl_id' => $this->rstl_id,
            'pstc_id' => $this->pstc_id,
            'testname_id' => $this->testname_id,
            'method_id' => $this->method_id,
            'fee' => $this->fee,
            'quantity' => $this->quantity,
            'is_package' => $this->is_package,
            'is_package_name' => $this->is_package_name,
            'local_analysis_id' => $this->local_analysis_id,
            'local_sample_id' => $this->local_sample_id,
			'analysis_offered' => $this->analysis_offered,
            'cancelled' => $this->cancelled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'pstc_sample_id', $this->pstc_sample_id])
            ->andFilterWhere(['like', 'testname', $this->testname])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'reference', $this->reference]);

        return $dataProvider;
    }
}
