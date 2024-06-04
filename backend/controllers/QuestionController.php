<?php

namespace backend\controllers;

use backend\models\Answer;
use backend\models\Category;
use backend\models\Question;
use backend\models\QuestionSearch;
use backend\models\User;
use common\models\constants\StatusConstant;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
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
                            'matchCallback' => function ($rule, $action) {
                                return User::isUserAdmin(Yii::$app->user->identity->_username);
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Question models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
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
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Question();
        $answer1 = new Answer();
        $answer2 = new Answer();
        $answer3 = new Answer();
        $answer4 = new Answer();
        $answers[] = $answer1;
        $answers[] = $answer2;
        $answers[] = $answer3;
        $answers[] = $answer4;

        if ($this->request->isPost) {
            if (
                $model->load($this->request->post()) && Answer::loadMultiple($answers, $this->request->post())
            ) {
                if ($model->save()) {
                    foreach ($answers as $answer) {
                        $answer->questionId = $model->id;
                        $answer->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $categories = Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all();

        return $this->render('create', [
            'model' => $model,
            'answers' => $answers,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
        ]);
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $answers = Answer::find()->where(['=', 'questionId', $id])->andWhere(['=', 'status', StatusConstant::ACTIVE])->all();

        if ($this->request->isPost) {
            if (
                $model->load($this->request->post()) && Answer::loadMultiple($answers, $this->request->post())
            ) {
                if ($model->save()) {
                    foreach ($answers as $answer) {
                        $answer->questionId = $model->id;
                        $answer->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $categories = Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all();

        return $this->render('update', [
            'model' => $model,
            'answers' => $answers,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
        ]);
    }

    /**
     * Deletes an existing Question model.
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

    public function actionDeleteSurveyQuestion($id, $surveyId)
    {
        $question = $this->findModel($id);
        if ($question)
        {
            $answer = Answer::deleteAll(['=', 'questionId', $id]);
            $question->delete();
            return $this->redirect(['survey/view', 'id' => $surveyId]);
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
