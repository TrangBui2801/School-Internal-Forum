<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Thread;

/**
 * ThreadSearch represents the model behind the search form of `backend\models\Thread`.
 */
class ThreadSearch extends Thread
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'topicId', 'status', 'type', 'moderatorId', 'created_by', 'updated_by'], 'integer'],
            [['name', 'short_description', 'sDescription','description', 'image', 'created_at', 'updated_at'], 'safe'],
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
        $query = Thread::find();

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
            'id' => $this->id,
            'topicId' => $this->topicId,
            'status' => $this->status,
            'type' => $this->type,
            'moderatorId' => $this->moderatorId,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'sDescription' => $this->sDescription,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'sDescription', $this->sDescription]);

        return $dataProvider;
    }
}
