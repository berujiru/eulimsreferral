<?php

namespace common\models\referral;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\referral\Pstcattachment;

/**
 * PstcattachmentSearch represents the model behind the search form of `common\models\referral\Pstcattachment`.
 */
class PstcattachmentSearch extends Pstcattachment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pstc_attachment_id', 'pstc_request_id', 'uploadedby_user_id'], 'integer'],
            [['filename', 'uploadedby_name', 'upload_date'], 'safe'],
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
        $query = Pstcattachment::find();

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
            'pstc_attachment_id' => $this->pstc_attachment_id,
            'pstc_request_id' => $this->pstc_request_id,
            'uploadedby_user_id' => $this->uploadedby_user_id,
            'upload_date' => $this->upload_date,
        ]);

        $query->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'uploadedby_name', $this->uploadedby_name]);

        return $dataProvider;
    }
}
