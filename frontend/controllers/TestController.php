<?php

namespace frontend\controllers;

use common\models\constants\StatusConstant;
use common\models\constants\TestTypeConstant;
use frontend\models\Category;
use Yii;
use yii\web\Controller;
use frontend\models\Test;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\TestSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * TestController implements the CRUD actions for Test model.
 */
class TestController extends Controller
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
     * Lists all Test models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Test model.
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
     * Creates a new Test model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Test();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->userId = Yii::$app->user->identity->id;
                $model->type = TestTypeConstant::TYPE_TEST;
                $model->can_modify = TestTypeConstant::TEST_CANNOT_MODIFY;
                $model->start_date = null;
                $model->end_date = null;
                $model->save();
                return $this->redirect(['test-detail/test', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $categories = Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all();
        return $this->render('create', [
            'model' => $model,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
        ]);
    }

    /**
     * Finds the Test model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Test the loaded model
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
