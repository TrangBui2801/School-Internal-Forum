<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%test_detail}}".
 *
 * @property int $id
 * @property int|null $testId
 * @property int|null $questionId
 * @property int|null $status
 * @property int|null $answerId
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Answer $answer
 * @property Question $question
 * @property Test $test
 */
class TestDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['testId', 'questionId', 'status', 'answerId', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['answerId'], 'exist', 'skipOnError' => true, 'targetClass' => Answer::class, 'targetAttribute' => ['answerId' => 'id']],
            [['questionId'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['questionId' => 'id']],
            [['testId'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['testId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'testId' => Yii::t('app', 'Test ID'),
            'questionId' => Yii::t('app', 'Question ID'),
            'status' => Yii::t('app', 'Status'),
            'answerId' => Yii::t('app', 'Answer ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Answer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(Answer::class, ['id' => 'answerId']);
    }

    /**
     * Gets query for [[Question]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::class, ['id' => 'questionId']);
    }

    /**
     * Gets query for [[Test]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'testId']);
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {  
            $this->created_at = new \yii\db\Expression('NOW()');  
            $this->created_by = Yii::$app->user->identity->id;
            $this->status = StatusConstant::ACTIVE;
        } else {  
            $this->updated_at = new \yii\db\Expression('NOW()');  
            $this->updated_by = Yii::$app->user->identity->id;  
        }  
        return parent::beforeSave($insert);  
    }
}
