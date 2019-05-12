<?php

namespace common\models\api;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\api\Migrationportal;

/**
 * MigrationportalSearch represents the model behind the search form of `common\models\api\Migrationportal`.
 */
class MigrationportalSearch extends Migrationportal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pm_id', 'record_id'], 'integer'],
            [['date_migrated', 'table_name'], 'safe'],
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
        $query = Migrationportal::find();

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
            'pm_id' => $this->pm_id,
            'date_migrated' => $this->date_migrated,
            'record_id' => $this->record_id,
        ]);

        $query->andFilterWhere(['like', 'table_name', $this->table_name]);

        return $dataProvider;
    }
}
