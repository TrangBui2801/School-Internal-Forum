<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%question}}".
 *
 * @property int $id
 * @property string|null $content
 * @property int|null $status
 * @property int|null $surveyId
 * @property int|null $threadId
 * @property int|null $score
 * @property int|null $levelId
 * @property int|null $picked_count
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Answer[] $answers
 * @property TestLevel $level
 * @property TestDetail[] $testDetails
 * @property Thread $thread
 */
class Question extends \yii\db\ActiveRecord
{
    public $categoryId;
    public $topicId;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['status', 'surveyId', 'threadId', 'score', 'levelId', 'picked_count', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['levelId'], 'exist', 'skipOnError' => true, 'targetClass' => TestLevel::class, 'targetAttribute' => ['levelId' => 'id']],
            [['threadId'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::class, 'targetAttribute' => ['threadId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'categoryId' => Yii::t('app', 'Category'),
            'topicId' => Yii::t('app', 'Topic'),
            'threadId' => Yii::t('app', 'Thread ID'),
            'score' => Yii::t('app', 'Score'),
            'levelId' => Yii::t('app', 'Level ID'),
            'picked_count' => Yii::t('app', 'Picked Count'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Answers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['questionId' => 'id']);
    }

    /**
     * Gets query for [[Level]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(TestLevel::class, ['id' => 'levelId']);
    }

    /**
     * Gets query for [[TestDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestDetails()
    {
        return $this->hasMany(TestDetail::class, ['questionId' => 'id']);
    }

    /**
     * Gets query for [[Thread]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(Thread::class, ['id' => 'threadId']);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {

            $this->picked_count = 0;
            $this->created_at = new \yii\db\Expression('NOW()');
            $this->created_by = Yii::$app->user->identity->id;
        } else {
            $this->updated_at = new \yii\db\Expression('NOW()');
            $this->updated_by = Yii::$app->user->identity->id;
        }
        return parent::beforeSave($insert);
    }

    public static function getQuestionOfSurvey($id)
    {
        return Question::find()->where(['=', 'surveyId', $id])->andWhere(['=', 'status', StatusConstant::ACTIVE])->all();
    }

    public static function getChartDataOfSurvey($id)
    {
        $question = Question::find()->where(['=', 'id', $id])->andWhere(['=', 'status', StatusConstant::ACTIVE])->one();
        $chart_data =  [];
        if ($question) {
            $answers = Answer::find()->where(['=', 'questionId', $question->id])->andWhere(['=', 'status', StatusConstant::ACTIVE])->all();
            if ($answers)
            {
                foreach ($answers as $ansKey => $answer) {
                    $title = "Answer " . ($ansKey + 1);
                    $data = count($answer->getTestDetails()->all());
                    $chart_data[] = ['key' => $title, 'value' => $data];
                }
            }
        }
        return $chart_data;
    }
}
