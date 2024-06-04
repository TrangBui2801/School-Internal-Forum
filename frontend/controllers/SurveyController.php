<?php

namespace frontend\controllers;

use Codeception\Util\HttpCode;
use common\models\constants\StatusConstant;
use common\models\constants\SurveyRemindConstant;
use frontend\models\Question;
use frontend\models\Survey;
use frontend\models\SurveySearch;
use frontend\models\Test;
use frontend\models\TestDetail;
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
     * Displays a single Survey model.
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

    public function actionNotRemindSurvey()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($this->request->isPost) {
            $data = $this->request->post();
            $this->updateNotRemind($data['surveyId']);
            return [
                'status' => HttpCode::OK
            ];
        }
    }

    protected function updateNotRemind($id)
    {
        $survey = Survey::find()->where(['=', 'id', $id])->andWhere(['=', 'status', StatusConstant::ACTIVE])->one();
        if ($survey && $survey->is_remind != SurveyRemindConstant::NOT_REMIND_AGAIN) {
            $survey->is_remind = SurveyRemindConstant::NOT_REMIND_AGAIN;
            $survey->save();
        }
    }

    public function actionTakeSurvey($id, $surveyId)
    {
        $this->updateNotRemind($id);
        $model = Test::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['=', 'id', $surveyId])->one();
        if ($model) {
            $questions = Question::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['=', 'surveyId', $surveyId])->all();
            if ($this->request->isPost) {
                $data = $this->request->post();
                for ($i = 1; $i <= count($questions); $i++) {
                    $ans = 'q' . $i;
                    $user_answer = explode("-", $data[$ans]);
                    if ($user_answer[0] != "" && $user_answer[1] != "") {
                        $question = Question::find()->where(['=', 'id', $user_answer[0]])->one();
                        if ($question) {
                            $test_detail = TestDetail::find()->innerJoin('survey', 'test_detail.testId = survey.surveyId')->where(['=', 'survey.userId', Yii::$app->user->identity->id])->andWhere(['=', 'survey.id', $id])->andWhere(['=', 'test_detail.questionId', $user_answer[0]])->one();
                            if (!$test_detail) {
                                $test_detail = new TestDetail();
                                $test_detail->testId = $surveyId;
                                $test_detail->questionId = $user_answer[0];
                            }
                            $test_detail->answerId = $user_answer[1];
                            $test_detail->save();
                        }
                    }
                }
                Yii::$app->session->setFlash('success', 'Submit survey success. Thank for your submission.');
                return $this->goHome();
            }
            $checked_answers = [];
            $answers = TestDetail::find()->where(['=', 'testId', $surveyId])->all();
            foreach ($answers as $answer) {
                $checked_answers[] = $answer->answerId;
            }
            return $this->render('view', [
                'survey' => $model,
                'questions' => $questions,
                'checked_answers' => $checked_answers,
                'surveyId' => $surveyId,
            ]);
        }

        // Kiểm tra model có tồn tại
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
        if (($model = Survey::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
