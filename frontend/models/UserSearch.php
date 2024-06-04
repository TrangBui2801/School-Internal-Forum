<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\User;

/**
 * UserSearch represents the model behind the search form of `frontend\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'departmentId', 'role', 'status', 'created_by', 'updated_by'], 'integer'],
            [['_username', '_password', 'full_name', 'email', 'address', 'phone_number', 'birthday', 'avatar', 'gender', 'introduction', 'short_introduction', 'facebook_link', 'skype_link', 'github_link', 'youtube_link', 'auth_key', 'created_at', 'updated_at'], 'safe'],
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
        $query = User::find();

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
            'departmentId' => $this->departmentId,
            'role' => $this->role,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', '_username', $this->_username])
            ->andFilterWhere(['like', '_password', $this->_password])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'introduction', $this->introduction])
            ->andFilterWhere(['like', 'short_introduction', $this->short_introduction])
            ->andFilterWhere(['like', 'facebook_link', $this->facebook_link])
            ->andFilterWhere(['like', 'skype_link', $this->skype_link])
            ->andFilterWhere(['like', 'github_link', $this->github_link])
            ->andFilterWhere(['like', 'youtube_link', $this->youtube_link])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
