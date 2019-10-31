<?php

namespace common\models\referraladmin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referraladmin\LabSampletype;

/**
 * LabSampletypeSearch represents the model behind the search form about `common\models\referraladmin\LabSampletype`.
 */
class LabSampletypeSearch extends LabSampletype
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['labsampletype_id', 'lab_id', 'sampletype_id'], 'integer'],
            [['date_added', 'added_by'], 'safe'],
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
        $query = LabSampletype::find();

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
            'labsampletype_id' => $this->labsampletype_id,
            'lab_id' => $this->lab_id,
            'sampletype_id' => $this->sampletype_id,
            'date_added' => $this->date_added,
        ]);

        $query->andFilterWhere(['like', 'added_by', $this->added_by]);

        return $dataProvider;
    }
}
