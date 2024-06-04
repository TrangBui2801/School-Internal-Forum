<?php

namespace frontend\controllers;

use backend\models\Answer;
use backend\models\Question;
use backend\models\Test;
use common\models\constants\StatusConstant;
use common\models\constants\TestAnswerConstant;
use common\models\constants\TestLevelConstant;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\TestDetail;
use yii\web\NotFoundHttpException;
use frontend\models\TestDetailSearch;
use yii\helpers\Json;

/**
 * TestDetailController implements the CRUD actions for TestDetail model.
 */
class TestDetailController extends Controller
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TestDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TestDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TestDetail model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TestDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TestDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionTest($id)
    {
        // Kiểm tra model có tồn tại
        $model = Test::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['=', 'id', $id])->one();
        if ($model) {
            //Kiểm tra đã làm test chưa bằng cách tìm các câu hỏi đã có trong bài test chưa
            $exist_questions = Question::find()->innerJoin('test_detail', 'question.id = test_detail.questionId')->where(['=', 'question.status', StatusConstant::ACTIVE])->andWhere(['=', 'test_detail.status', StatusConstant::ACTIVE])->andWhere(['=', 'test_detail.testId', $id])->all();
            if (!$exist_questions || count($exist_questions) == 0) {
                $questions_beginner = Question::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['IS', 'surveyId', NULL])->andWhere(['=', 'levelId', TestLevelConstant::BEGINNER])->orderBy(['picked_count' => SORT_ASC]);
                $questions_intermediate = Question::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['IS', 'surveyId', NULL])->andWhere(['=', 'levelId', TestLevelConstant::INTERMEDIATE])->orderBy(['picked_count' => SORT_ASC]);
                $questions_advanced = Question::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['IS', 'surveyId', NULL])->andWhere(['=', 'levelId', TestLevelConstant::ADVANCED])->orderBy(['picked_count' => SORT_ASC]);
                if ($model->levelId == TestLevelConstant::BEGINNER) {
                    //10 câu dễ
                    $questions_beginner->limit(10);
                } else if ($model->levelId == TestLevelConstant::INTERMEDIATE) {
                    //7 câu dễ, 3 câu TB
                    $questions_beginner->limit(7);
                    $questions_intermediate->limit(3);
                    $questions_beginner->union($questions_intermediate, true)->all();
                } else if ($model->levelId == TestLevelConstant::ADVANCED) {
                    //3 dễ, 4 TB, 3 khó
                    $questions_beginner->limit(3);
                    $questions_intermediate->limit(4);
                    $questions_advanced->limit(3);
                    $questions_beginner->union($questions_intermediate, true)->union($questions_advanced, true);
                }
                $questions = $questions_beginner->all();
                $maxScore = 0;
                foreach ($questions as $question) {
                    $maxScore += $question->score;
                    $question->picked_count++;
                    $question->save();
                    $test_detail = TestDetail::find()->where(['=', 'testId', $model->id])->andWhere(['=', 'questionId', $question->id])->one();
                    if (!$test_detail) {
                        $test_detail = new TestDetail();
                        $test_detail->testId = $model->id;
                        $test_detail->questionId = $question->id;
                        $test_detail->save();
                    }
                }
                //Tạo mới các model 
                return $this->render('test', [
                    'test' => $model,
                    'questions' => $questions,
                    'isFinished' => false,
                    'maxScore' => $maxScore,
                ]);
            }
            else
            {
                $maxScore = 0;
                foreach ($exist_questions as $question)
                {
                    $test_detail = TestDetail::find()->where(['=', 'testId', $model->id])->andWhere(['=', 'questionId', $question->id])->one();
                    foreach ($question->answers as $answer)
                    {
                        if ($answer->id == $test_detail->answerId)
                        {
                            $answer->isChosen = true;
                        }
                    }
                    $maxScore += $question->score;
                }
                return $this->render('test', [
                    'test' => $model,
                    'questions' => $exist_questions,
                    'isFinished' => true,
                    'maxScore' => $maxScore,
                ]);
            }
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionAnswer()
    {
        $score = 0;
        // get data from ajax
        if ($this->request->isPost) {
            $data = $this->request->post();
            $test = Test::find()->where(['=', 'id', $data['testId']])->one();
            if ($test) {                
                if (isset($data['userAns']))
                {
                    foreach ($data['userAns'] as $answer) {
                        $user_answer = explode("-", $answer);
                        if ($user_answer[0] != "" && $user_answer[1] != "") {
                            $question = Question::find()->where(['=', 'id', $user_answer[0]])->one();
                            if ($question) {
                                $test_detail = TestDetail::find()->where(['=', 'testId', $data['testId']])->andWhere(['=', 'questionId', $user_answer[0]])->one();
                                $correct_ans = Answer::find()->where(['=', 'id', $user_answer[1]])->andWhere(['=', 'is_correct', TestAnswerConstant::ANSWER_CORRECT])->one();
                                if ($correct_ans) {
                                    $score += $question->score;
                                }
                                $test_detail->answerId = $user_answer[1];
                                $test_detail->save();
                            }
                        }
                    }
                }
                $test->score = $score;
                $test->save();
            }
        }
        return $score;
    }

    /**
     * Updates an existing TestDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TestDetail model.
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
     * Finds the TestDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TestDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestDetail::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
