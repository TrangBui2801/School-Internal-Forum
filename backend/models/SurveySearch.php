<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Survey;
use common\models\constants\TestTypeConstant;

/**
 * SurveySearch represents the model behind the search form of `backend\models\Survey`.
 */
class SurveySearch extends Test
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at',], 'safe'],
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
        $query = Test::find()->where(['=', 'type', TestTypeConstant::TYPE_SURVEY]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['newColumn']=[
            'asc'=>['test.end_date'=> SORT_ASC],
            'desc'=>['test.end_date'=> SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'userId' => $this->userId,
            'threadId' => $this->threadId,
            'levelId' => $this->levelId,
            'score' => $this->score,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'start_date', $this->created_at])
            ->andFilterWhere(['like', 'end_date', $this->updated_at]);
        return $dataProvider;
    }
}
