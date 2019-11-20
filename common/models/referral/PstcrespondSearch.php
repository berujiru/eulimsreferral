<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Pstcrespond;

/**
 * PstcrespondSearch represents the model behind the search form of `common\models\referral\Pstcrespond`.
 */
class PstcrespondSearch extends Pstcrespond
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pstc_respond_id', 'rstl_id', 'pstc_id', 'pstc_request_id', 'local_request_id', 'lab_id'], 'integer'],
            [['request_ref_num', 'request_date_created', 'estimated_due_date', 'created_at', 'updated_at'], 'safe'],
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
        $query = Pstcrespond::find();

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
            'pstc_respond_id' => $this->pstc_respond_id,
            'rstl_id' => $this->rstl_id,
            'pstc_id' => $this->pstc_id,
            'pstc_request_id' => $this->pstc_request_id,
            'local_request_id' => $this->local_request_id,
            'request_date_created' => $this->request_date_created,
            'estimated_due_date' => $this->estimated_due_date,
            'lab_id' => $this->lab_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'request_ref_num', $this->request_ref_num]);

        return $dataProvider;
    }
}
