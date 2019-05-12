<?php

namespace common\models\system;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\system\ApiSettings;

/**
 * ApiSettingsSearch represents the model behind the search form about `common\models\system\ApiSettings`.
 */
class ApiSettingsSearch extends ApiSettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['api_settings_id', 'rstl_id', 'created_at', 'updated_at'], 'integer'],
            [['api_url', 'get_token_url', 'request_token'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = ApiSettings::find();

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
            'api_settings_id' => $this->api_settings_id,
            'rstl_id' => $this->rstl_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'api_url', $this->api_url])
            ->andFilterWhere(['like', 'get_token_url', $this->get_token_url])
            ->andFilterWhere(['like', 'request_token', $this->request_token]);

        return $dataProvider;
    }
}
