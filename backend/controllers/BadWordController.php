<?php

namespace backend\controllers;

use backend\models\AppConfig;
use backend\models\BadWord;
use backend\models\BadWordSearch;
use backend\models\ConfigType;
use common\models\constants\ConfigTypeConstant;
use common\models\constants\StatusConstant;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BadWordController implements the CRUD actions for BadWord model.
 */
class BadWordController extends Controller
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
     * Lists all BadWord models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BadWordSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $data = AppConfig::find()->innerJoin('config_type', 'app_config.config_typeId = config_type.id')->where(['=', 'config_type.name', ConfigTypeConstant::BAD_WORDS])->andWhere(['=', 'app_config.status', StatusConstant::ACTIVE])->asArray()->one();
        $isConfigExist = $data && count($data) > 0;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isConfigExist' => $isConfigExist,
        ]);
    }

    /**
     * Displays a single BadWord model.
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
     * Creates a new BadWord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $badWordConfig = AppConfig::find()->innerJoin('config_type', 'app_config.config_typeId = config_type.id')->where(['=', 'config_type.name', ConfigTypeConstant::BAD_WORDS])
                                          ->andWhere(['=', 'app_config.status', StatusConstant::ACTIVE])->one();
        if ($badWordConfig) {
            Yii::$app->session->setFlash('warning', 'Config already exist.\nYou can only update or delete to create new config for the system');
            $this->redirect(['update', 'id' => $badWordConfig->id]);
        } else {
            $model = new BadWord();
            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    $configType = ConfigType::find()->where(['=', 'name', ConfigTypeConstant::BAD_WORDS])->one();
                    if (!$configType) {
                        $configType = new ConfigType();
                        $configType->name = ConfigTypeConstant::BAD_WORDS;
                        $configType->status = StatusConstant::ACTIVE;
                    }
                    $model->config_typeId = $configType->id;
                    $model->status = StatusConstant::ACTIVE;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Create bad word list success');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    Yii::$app->session->setFlash('error', 'Cannot create bad word list');
                    $model->loadDefaultValues();
                }
            } else {
                $model->loadDefaultValues();
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BadWord model.
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
     * Deletes an existing BadWord model.
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
     * Finds the BadWord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return BadWord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BadWord::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
