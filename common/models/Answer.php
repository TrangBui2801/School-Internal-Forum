<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%answer}}".
 *
 * @property int $id
 * @property int|null $questionId
 * @property string|null $content
 * @property string|null $explanation
 * @property int|null $status
 * @property int $is_correct
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Question $question
 * @property TestDetail[] $testDetails
 */
class Answer extends \yii\db\ActiveRecord
{
    public $isChosen;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%answer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['questionId', 'status', 'is_correct', 'created_by', 'updated_by', 'isChosen'], 'integer'],
            [['content', 'explanation'], 'string'],
            [['is_correct'], 'required'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['questionId'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['questionId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'questionId' => Yii::t('app', 'Question ID'),
            'content' => Yii::t('app', 'Content'),
            'explanation' => Yii::t('app', 'Explanation'),
            'status' => Yii::t('app', 'Status'),
            'isChosen' => Yii::t('app', 'Is chosen'),
            'is_correct' => Yii::t('app', 'Is Correct'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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
     * Gets query for [[TestDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestDetails()
    {
        return $this->hasMany(TestDetail::class, ['answerId' => 'id']);
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
