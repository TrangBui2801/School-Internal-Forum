<?php

namespace backend\controllers;

use backend\models\Thread;
use backend\models\ThreadSearch;
use backend\models\Topic;
use backend\models\User;
use common\helpers\ImageUrlHelper;
use common\models\constants\StatusConstant;
use common\models\constants\ThreadTypeConstant;
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
 * ThreadController implements the CRUD actions for Thread model.
 */
class ThreadController extends Controller
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
     * Lists all Thread models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ThreadSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Thread model.
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
     * Creates a new Thread model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Thread();
        $all_files = array();
        $all_files_preview = array();
        $files_type = array();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $folder_name = "thread_" . $model->name;
                $files = UploadedFile::getInstances($model, 'file');
                if ($files) {
                    FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/' . $folder_name, $mode = 0775, $recursive = true);
                    foreach ($files as $file) {
                        $url = Url::to('@backend') . '/web/uploads/' . $folder_name . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                        $file->saveAs($url);
                        $model->image = $url;
                    }
                } else {
                    $model->image = Url::to('@backend') . '/web/uploads/threads/thread_no_image/no-image.png';
                }
                $model->type = ThreadTypeConstant::PUBLIC_THREAD;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $topics = ArrayHelper::map(Topic::find()->where(['=', 'status', StatusConstant::ACTIVE])->all(), 'id', 'name');
        $moderatorIds = ArrayHelper::map(User::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['<>', 'role', UserRoleConstant::ADMIN])->all(), 'id', 'full_name');

        return $this->render('create', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'topics' => $topics,
            'moderatorIds' => $moderatorIds,
        ]);
    }

    /**
     * Updates an existing Thread model.
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
                        FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/' . $folder_name, $mode = 0775, $recursive = true);
                        $url = Url::to('@backend') . '/web/uploads/' . $folder_name . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                        $model->image = $url;
                    }
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $topics = ArrayHelper::map(Topic::find()->where(['=', 'status', StatusConstant::ACTIVE])->all(), 'id', 'name');
        $moderatorIds = ArrayHelper::map(User::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['<>', 'role', UserRoleConstant::ADMIN])->all(), 'id', 'full_name');

        return $this->render('update', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'topics' => $topics,
            'moderatorIds' => $moderatorIds,
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
     * Deletes an existing Thread model.
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
     * Finds the Thread model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Thread the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Thread::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
