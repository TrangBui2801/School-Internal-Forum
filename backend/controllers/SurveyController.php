<?php

namespace backend\controllers;

use backend\models\Answer;
use backend\models\Question;
use backend\models\Survey;
use backend\models\SurveySearch;
use backend\models\Test;
use backend\models\User;
use common\models\constants\StatusConstant;
use common\models\constants\SurveyRemindConstant;
use common\models\constants\TestAnswerConstant;
use common\models\constants\TestTypeConstant;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SurveyController implements the CRUD actions for Survey model.
 */
class SurveyController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Survey models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SurveySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Survey model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'questions' => Question::getQuestionOfSurvey($id),
        ]);
    }

    /**
     * Creates a new Survey model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Test();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->userId = null;
                $model->threadId = null;
                $model->levelId = null;
                $model->can_modify = TestTypeConstant::TEST_CAN_MODIFY;
                $model->type = TestTypeConstant::TYPE_SURVEY;
                if ($model->save()) {
                    $users = User::find()->select(['id'])->where(['=', 'status', StatusConstant::ACTIVE])->all();
                    if ($users)
                    {
                        $data = array();
                        $columns = ['userId', 'surveyId', 'is_remind', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'];
                        foreach ($users as $user)
                        {
                            $data[] = [
                                'userId' => $user->id,
                                'surveyId' => $model->id,
                                'is_remind' => SurveyRemindConstant::REMIND_AGAIN,
                                'status' => StatusConstant::ACTIVE,
                                'created_at' => new \yii\db\Expression('NOW()'),
                                'created_by' => Yii::$app->user->identity->id,
                                'updated_at' => null,
                                'updated_by' => null,
                            ];
                        }
                        Yii::$app->db->createCommand()->batchInsert('survey', $columns, $data)->execute();
                    }
                    return $this->redirect(['create-survey-questions', 'id' => $model->id, 'questionNumber' => $model->surveyQuestionNumber, 'questionOrder' => 1]);
                }
                $model->loadDefaultValues();
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateSurveyQuestions($id, $questionNumber, $questionOrder)
    {
        $survey = Test::findOne(['id' => $id]);
        if ($survey) {
            if ($questionNumber >= $questionOrder) {
                $model = new Question();
                $answer1 = new Answer();
                $answer2 = new Answer();
                $answer3 = new Answer();
                $answer4 = new Answer();
                $answer5 = new Answer();

                $answers[] = $answer1;
                $answers[] = $answer2;
                $answers[] = $answer3;
                $answers[] = $answer4;
                $answers[] = $answer5;

                if ($this->request->isPost) {
                    if ($model->load($this->request->post()) && Answer::loadMultiple($answers, $this->request->post())) {
                        $model->surveyId = $id;
                        if ($model->save()) {
                            foreach ($answers as $answer) {
                                $answer->questionId = $model->id;
                                $answer->is_correct = TestAnswerConstant::ANSWER_INCORRECT;
                                $answer->save();
                            }
                        }
                        $questionOrder++;
                        return $this->redirect(['create-survey-questions', 'id' => $id, 'questionNumber' => $questionNumber, 'questionOrder' => $questionOrder]);
                    }
                } else {
                    $model->loadDefaultValues();
                }
                return $this->render('create_question', [
                    'model' => $model,
                    'answers' => $answers,
                    'questionOrder' => $questionOrder,
                ]);
            }
            return $this->redirect(['view', 'id' => $id]);
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Updates an existing Survey model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $currentNumberQuestion = count($model->questions);
        $model->surveyQuestionNumber = $currentNumberQuestion;
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->surveyQuestionNumber < $currentNumberQuestion)
            {
                $removedQuestions = Question::find()->where(['=', 'surveyId', $id])->limit($currentNumberQuestion - $model->surveyQuestionNumber)->offset($model->surveyQuestionNumber)->all();
                if ($removedQuestions)
                {
                    $removedIds = [];
                    foreach($removedQuestions as $item)
                    {
                        $removedIds[] = $item->id;
                    }
                    Answer::deleteAll(['IN', 'questionId', $removedIds]);
                    Question::deleteAll(['IN', 'id', $removedIds]);
                }
            }
            return $this->redirect(['update-survey-questions', 'id' => $model->id, 'questionNumber' => $model->surveyQuestionNumber, 'questionOrder' => 1]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateSurveyQuestions($id, $questionNumber, $questionOrder)
    {
        $survey = Test::findOne(['id' => $id]);
        if ($survey) {
            if ($questionNumber >= $questionOrder) {
                $model = Question::find()->where(['=', 'surveyId', $id])->limit(1)->offset($questionOrder - 1)->one();
                if ($model) {
                    $answers = Answer::find()->where(['=', 'questionId', $model->id])->andWhere(['=', 'status', StatusConstant::ACTIVE])->all();
                } else {
                    $model = new Question();
                    $answer1 = new Answer();
                    $answer2 = new Answer();
                    $answer3 = new Answer();
                    $answer4 = new Answer();
                    $answers[] = $answer1;
                    $answers[] = $answer2;
                    $answers[] = $answer3;
                    $answers[] = $answer4;
                }
                if ($this->request->isPost) {
                    if ($model->load($this->request->post()) && Answer::loadMultiple($answers, $this->request->post())) {
                        if ($model->save()) {
                            foreach ($answers as $answer) {
                                $answer->questionId = $model->id;
                                $answer->save();
                            }
                        }
                        $questionOrder++;
                        return $this->redirect(['update-survey-questions', 'id' => $id, 'questionNumber' => $questionNumber, 'questionOrder' => $questionOrder]);
                    }
                } else {
                    $model->loadDefaultValues();
                }
                return $this->render('update_question', [
                    'model' => $model,
                    'answers' => $answers,
                    'questionOrder' => $questionOrder,
                ]);
            }
            return $this->redirect(['view', 'id' => $id]);
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Deletes an existing Survey model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Survey model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Survey the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Test::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
