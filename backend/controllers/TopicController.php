<?php

namespace backend\controllers;

use backend\models\Category;
use backend\models\Topic;
use backend\models\TopicSearch;
use backend\models\User;
use common\helpers\ImageUrlHelper;
use common\models\constants\StatusConstant;
use common\models\constants\UserRoleConstant;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * TopicController implements the CRUD actions for Topic model.
 */
class TopicController extends Controller
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
     * Lists all Topic models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TopicSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Topic model.
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
     * Creates a new Topic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Topic();
        $all_files = array();
        $all_files_preview = array();
        $files_type = array();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $folder_name = "thread_" . $model->name;
                $files = UploadedFile::getInstances($model, 'file');
                if (count($files) > 0) {
                    FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/topics/' . $folder_name, $mode = 0775, $recursive = true);
                    foreach ($files as $file) {
                        $url = Url::to('@backend') . '/web/uploads/topics/' . $folder_name . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                        $file->saveAs($url);
                        $model->image = $url;
                    }
                } else {
                    $model->image = Url::to('@backend') . '/web/uploads/topics/topic_no_image/no-image.png';
                }
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $categories = ArrayHelper::map(Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all(), 'id', 'name');
        $moderatorIds = ArrayHelper::map(User::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['<>', 'role', UserRoleConstant::ADMIN])->all(), 'id', 'full_name');

        return $this->render('create', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'categories' => $categories,
            'moderatorIds' => $moderatorIds,
        ]);
    }

    /**
     * Updates an existing Topic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $all_files = array();
        $all_files_preview = array();
        $files_type = array();
        $folder_url = "";
        if ($model->image) {
            $folder_url = substr($model->image, 0, strripos($model->image, '/'));
            $all_files[] = ImageUrlHelper::getImageUrl($model->image);
            $obj = (object) array('caption' => $model->name . " avatar", 'url' => "/index.php?r=idea%2Fdelete-file&id=$model->id", 'key' => $model->id, 'type' => "image");
            $files_type[] = "image";
            $all_files_preview[] = $obj;
        }
        if ($this->request->isPost && $model->load($this->request->post())) {
            $files = UploadedFile::getInstances($model, 'file');
            if ($files) {
                foreach ($files as $file) {
                    if ($folder_url != '' && file_exists($folder_url)) {
                        $url = $folder_url . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                    } else {
                        $folder_name = "thread_" . $model->id;
                        FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/topics/' . $folder_name, $mode = 0775, $recursive = true);
                        $url = Url::to('@backend') . '/web/uploads/topics/' . $folder_name . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                    }
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $categories = ArrayHelper::map(Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all(), 'id', 'name');
        $moderatorId = ArrayHelper::map(User::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['<>', 'role', UserRoleConstant::ADMIN])->all(), 'id', 'name');

        return $this->render('update', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'categories' => $categories,
            'moderatorIds' => $moderatorId,
        ]);
    }

    private function deleteFile($url)
    {
        if ($url) {
            $folder_url = substr($url->url, 0, strripos($url->url, '/'));
            if ($folder_url) {
                $this->rmdir_recursive($folder_url);
            }
        }
    }

    /**
     * Deletes an existing Topic model.
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
     * Finds the Topic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Topic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Topic::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
