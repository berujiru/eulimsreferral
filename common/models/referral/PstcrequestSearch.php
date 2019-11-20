<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Pstcrequest;

/**
 * PstcrequestSearch represents the model behind the search form of `common\models\referral\Pstcrequest`.
 */
class PstcrequestSearch extends Pstcrequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pstc_request_id', 'rstl_id', 'pstc_id', 'customer_id', 'discount_id', 'status_id', 'accepted', 'local_request_id'], 'integer'],
            [['submitted_by', 'received_by', 'created_at', 'updated_at','sample_received_date'], 'safe'],
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
		$rstlId = (int) Yii::$app->user->identity->profile->rstl_id;
		$pstcId = (int) Yii::$app->user->identity->profile->pstc_id;
		
        $query = Pstcrequest::find()
			->where('rstl_id =:rstlId AND pstc_id =:pstcId',[':rstlId'=>$rstlId,':pstcId'=>$pstcId])
			->orderBy('created_at DESC');

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
            'pstc_request_id' => $this->pstc_request_id,
            'rstl_id' => $this->rstl_id,
            'pstc_id' => $this->pstc_id,
            'customer_id' => $this->customer_id,
            'discount_id' => $this->discount_id,
            'status_id' => $this->status_id,
            'accepted' => $this->accepted,
            'local_request_id' => $this->local_request_id,
			'sample_received_date' => $this->sample_received_date,
			'is_referral' => $this->is_referral,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'submitted_by', $this->submitted_by])
            ->andFilterWhere(['like', 'received_by', $this->received_by])
            ->andFilterWhere(['=',"DATE_FORMAT(`created_at`, '%Y-%m-%d')",$this->created_at]);

        return $dataProvider;
    }
}
