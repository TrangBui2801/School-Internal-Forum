<?php

namespace backend\controllers;

use backend\models\Category;
use backend\models\File;
use backend\models\Post;
use backend\models\PostSearch;
use backend\models\Thread;
use backend\models\User;
use common\helpers\ImageUrlHelper;
use common\models\constants\FileTypeConstant;
use common\models\constants\StatusConstant;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
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
     * Lists all Post models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $all_files = array();
        $all_files_preview = array();
        $all_cover_files = array();
        $all_cover_files_preview = array();
        $files_type = array();
        $model = new Post();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $folder_name = 'post_' . $model->id;
                FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/cover', $mode = 0775, $recursive = true);
                FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/attachments', $mode = 0775, $recursive = true);

                //upload cover image
                $coverImage = UploadedFile::getInstances($model, 'cover_image');
                foreach ($coverImage as $file) {
                    $uploaded_filename = Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                    $url = Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/cover/' . $uploaded_filename;
                    $isUploaded = $file->saveAs($url);
                    if ($isUploaded) {
                        $attachment = new File();
                        $attachment->parentId = $model->id;
                        $attachment->file_type = FileTypeConstant::POST_IMAGE_COVER;
                        $attachment->file_extension = $this->getFileType($file->extension);
                        $attachment->url = Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/cover/' . $uploaded_filename;
                        $attachment->original_name = $file->name;
                        $attachment->save();
                    }
                }
                //upload attachment
                $files = UploadedFile::getInstances($model, 'attachment');
                foreach ($files as $file) {
                    $uploaded_filename = Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                    $url = Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/attachments/' . $uploaded_filename;
                    $isUploaded = $file->saveAs($url);
                    if ($isUploaded) {
                        $attachment = new File();
                        $attachment->parentId = $model->id;
                        $attachment->file_type = FileTypeConstant::POST_ATTACHMENT_FILE;
                        $attachment->file_extension = $this->getFileType($file->extension);
                        $attachment->url = Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/attachments/' . $uploaded_filename;
                        $attachment->original_name = $file->name;
                        $attachment->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $categories = Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all();

        return $this->render('create', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'all_cover_files' => $all_cover_files,
            'all_cover_files_preview' => $all_cover_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $all_files = array();
        $all_files_preview = array();
        $all_cover_files = array();
        $all_cover_files_preview = array();
        $files_type = array();
        $model = $this->findModel($id);
        //get cover image
        $uploaded_cover_image = File::find()->where(['=', 'parentId', $model->id])->andWhere(['=', 'file_type', FileTypeConstant::POST_IMAGE_COVER])->all();
        $folder_url = "";
        if ($uploaded_cover_image) {
            $folder_url = substr(end($uploaded_cover_image)->url, 0, strripos(end($uploaded_cover_image)->url, '/'));
            foreach ($uploaded_cover_image as $file) {
                $all_cover_files[] = ImageUrlHelper::getImageUrl($file->url);
                $obj = (object) array('caption' => $file->original_name, 'url' => Url::to(['post/delete-file', 'id' => $file->id]), 'key' => $file->id, 'type' => $file->file_extension);
                $files_type[] = $file->file_extension;
                $all_cover_files_preview[] = $obj;
            }
        }
        //get attachments
        $uploaded_file = File::find()->where(['=', 'parentId', $model->id])->andWhere(['=', 'file_type', FileTypeConstant::POST_ATTACHMENT_FILE])->all();
        $folder_url = "";
        if ($uploaded_file) {
            $folder_url = substr(end($uploaded_file)->url, 0, strripos(end($uploaded_file)->url, '/'));
            foreach ($uploaded_file as $file) {
                $all_files[] = ImageUrlHelper::getImageUrl($file->url);
                $obj = (object) array('caption' => $file->original_name, 'url' => Url::to(['post/delete-file', 'id' => $file->id]), 'key' => $file->id, 'type' => $file->file_extension);
                $files_type[] = $file->file_extension;
                $all_files_preview[] = $obj;
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            //reupload cover image
            $cover_image = UploadedFile::getInstances($model, 'cover_image');
            //check if new cover image is uploaded
            if (count($cover_image) > 0) {
                $removed_id = array();
                foreach ($uploaded_cover_image as $file) {
                    $removed_id[] = $file->id;
                }
                $this->deleteFiles($removed_id);
                foreach ($cover_image as $file) {
                    $folder_name = 'post_' . $model->id;
                    $uploaded_filename = Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                    if ($folder_url != '' && file_exists($folder_url)) {
                        $url = $folder_url . '/' . $uploaded_filename;
                    } else {
                        FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/cover', $mode = 0775, $recursive = true);
                        $files = UploadedFile::getInstances($model, 'attachment');
                        $url = Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/cover/' . $uploaded_filename;
                    }
                    $isUploaded = $file->saveAs($url);
                    if ($isUploaded) {
                        $attachment = new File();
                        $attachment->parentId = $model->id;
                        $attachment->file_type = FileTypeConstant::POST_IMAGE_COVER;
                        $attachment->file_extension = $this->getFileType($file->extension);
                        $attachment->url = Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/cover/' . $uploaded_filename;
                        $attachment->original_name = $file->name;
                        $attachment->save();
                    }
                }
            }

            //reupload attachments
            $files = UploadedFile::getInstances($model, 'attachment');
            //check if new attachments are uploaded
            if (count($files) > 0) {
                $removed_id = array();
                foreach ($uploaded_file as $file) {
                    $removed_id[] = $file->id;
                }
                $this->deleteFiles($removed_id);
                foreach ($files as $file) {
                    $folder_name = 'post_' . $model->id;
                    $uploaded_filename = Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                    if ($folder_url != '' && file_exists($folder_url)) {
                        $url = $folder_url . '/' . $uploaded_filename;
                    } else {
                        FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/attachments', $mode = 0775, $recursive = true);
                        $files = UploadedFile::getInstances($model, 'attachment');
                        $url = Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/attachments/' . $uploaded_filename;
                    }
                    $isUploaded = $file->saveAs($url);
                    if ($isUploaded) {
                        $attachment = new File();
                        $attachment->parentId = $model->id;
                        $attachment->file_type = FileTypeConstant::POST_ATTACHMENT_FILE;
                        $attachment->file_extension = $this->getFileType($file->extension);
                        $attachment->url = Url::to('@backend') . '/web/uploads/posts/' . $folder_name . '/attachments/' . $uploaded_filename;
                        $attachment->original_name = $file->name;
                        $attachment->save();
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $categories = Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all();

        return $this->render('update', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'all_cover_files' => $all_cover_files,
            'all_cover_files_preview' => $all_cover_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
        ]);
    }

    public function actionDeleteFile($id)
    {
        $file = File::findOne($id);
        $check = true;
        if ($check) {
            $file_location = Url::to('@backend') . '/web' . substr($file->url, strripos($file->url, '/uploads'), strlen($file->url));
            unlink($file_location);
        }
        $file->delete();
        return '{}';
    }

    public function deleteFiles($id)
    {
        $url = File::find()->select(['url'])->where(['IN', 'id', $id])->one();
        if ($url) {
            $folder_url = substr($url->url, 0, strripos($url->url, '/'));
            File::deleteAll(['IN', 'id', $id]);
            if (is_dir($folder_url)) {
                $this->rmdir_recursive($folder_url);
            }
        }
    }

    public function deleteFilesOfPost($id)
    {
        $url = File::find()->select(['url'])->where(['=', 'parentId', $id])->one();
        if ($url) {
            $folder_url = substr($url->url, 0, strripos($url->url, '/'));
            $check = File::deleteAll(['parentId' => $id]);
            if ($check && is_dir($folder_url)) {
                $this->rmdir_recursive($folder_url);
            }
        }
    }

    function rmdir_recursive($dir)
    {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir("$dir/$file")) $this->rmdir_recursive("$dir/$file");
            else unlink("$dir/$file");
        }
        rmdir($dir);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->identity->id == $model->authorId)
        {
            $this->deleteFilesOfPost($id);
            \Yii::$app
                ->db
                ->createCommand()
                ->delete('post', ['parentId' => $id])
                ->execute();
            \Yii::$app
                ->db
                ->createCommand()
                ->delete('reaction', ['postId' => $id])
                ->execute();
            $model->delete();
            return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    private function getFileType($extension)
    {
        $extension = "." . $extension;
        switch ($extension) {
            case  '.txt':
                return 'text';

            case '.doc':
            case '.docx':
            case '.ppt':
            case '.pptx':
            case '.xls':
            case '.xlsx':
                return 'office';

            case '.pdf':
                return 'pdf';

            case '.jpg':
            case '.jpeg':
            case '.png':
            case '.gif':
            case '.tif':
            case '.tiff':
                return 'image';

            case '.wav':
            case '.mp3':
            case '.m4a':
            case '.ogg':
            case '.flac':
            case '.wma':
            case '.aac':
            case '.gsm':
            case '.dct':
            case '.aiff':
            case '.au':
                return 'audio';

            default:
                return 'other';
        }
    }
}
