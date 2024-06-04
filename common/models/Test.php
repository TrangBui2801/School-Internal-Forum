<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%test}}".
 *
 * @property int $id
 * @property strin|null $title
 * @property int|null $userId
 * @property int|null $threadId
 * @property int|null $levelId
 * @property int|null $score
 * @property int|null $can_modify
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $type
 * @property int|null $status
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property TestLevel $level
 * @property TestDetail[] $testDetails
 * @property Thread $thread
 * @property User $user
 */
class Test extends \yii\db\ActiveRecord
{
    public $topicId;
    public $categoryId;
    public $surveyQuestionNumber;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'threadId', 'levelId', 'score', 'status', 'created_by', 'updated_by', 'can_modify', 'type', 'surveyQuestionNumber'], 'integer'],
            [['title', 'created_at', 'updated_at', 'start_date', 'end_date'], 'string', 'max' => 255],
            [['levelId'], 'exist', 'skipOnError' => true, 'targetClass' => TestLevel::class, 'targetAttribute' => ['levelId' => 'id']],
            [['threadId'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::class, 'targetAttribute' => ['threadId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
            ['end_date','validateDates'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'userId' => Yii::t('app', 'User'),
            'categoryId' => Yii::t('app', 'Category'),
            'topicId' => Yii::t('app', 'Topic'),
            'threadId' => Yii::t('app', 'Thread'),
            'levelId' => Yii::t('app', 'Level'),
            'score' => Yii::t('app', 'Score'),
            'can_modify' => Yii::t('app', 'Can modify'),
            'surveyQuestionNumber' => Yii::t('app', 'Number of question(s)'),
            'type' => Yii::t('app', 'Test type'),
            'start_date' => Yii::t('app', 'Survey start date'),
            'end_date' => Yii::t('app', 'Survey end date'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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
        return $this->hasMany(TestDetail::class, ['testId' => 'id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['surveyId' => 'id']);
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->score = 0;
            $this->status = StatusConstant::ACTIVE;
            $this->created_at = new \yii\db\Expression('NOW()');  
            $this->created_by = Yii::$app->user->identity->id;
        } else {  
            $this->updated_at = new \yii\db\Expression('NOW()');  
            $this->updated_by = Yii::$app->user->identity->id;  
        }  
        return parent::beforeSave($insert);  
    }

    public function validateDates(){
        if(strtotime($this->end_date) <= strtotime($this->start_date)){
            $this->addError('start_date','Please give correct Start and End dates');
            $this->addError('end_date','Please give correct Start and End dates');
        }
    }
}
