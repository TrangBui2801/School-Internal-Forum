<?php

namespace common\models;

use common\models\constants\StatusConstant;
use common\models\constants\SurveyRemindConstant;
use Yii;

/**
 * This is the model class for table "survey".
 *
 * @property int $id
 * @property int|null $userId
 * @property int|null $surveyId
 * @property int|null $is_remind
 * @property int|null $status
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Test $survey
 * @property User $user
 */
class Survey extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'surveyId', 'is_remind', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'created_by'], 'required'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['surveyId'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['surveyId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'surveyId' => Yii::t('app', 'Survey ID'),
            'is_remind' => Yii::t('app', 'Is Remind'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Survey]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSurvey()
    {
        return $this->hasOne(Test::class, ['id' => 'surveyId']);
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

    public static function getPopupSurvey()
    {
        $currentDate = new \yii\db\Expression('NOW()');
        return Survey::find()->innerJoin('test', 'survey.surveyId = test.id')
                             ->where(['=', 'survey.userId', Yii::$app->user->identity->id])
                             ->andWhere(['=', 'survey.status', StatusConstant::ACTIVE])
                             ->andWhere(['>=', "STR_TO_DATE(test.end_date, '%d-%m-%Y')", $currentDate])
                             ->andWhere(['<=', "STR_TO_DATE(test.start_date, '%d-%m-%Y')", $currentDate])
                             ->andWhere(['=', 'survey.is_remind', SurveyRemindConstant::REMIND_AGAIN])
                             ->andWhere(['=', 'test.status', StatusConstant::ACTIVE])
                             ->one();
    }
}
