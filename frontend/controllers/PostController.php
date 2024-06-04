<?php

namespace frontend\controllers;

use backend\models\BadWord;
use backend\models\Notification;
use backend\models\Thread;
use Codeception\Util\HttpCode;
use common\helpers\FirebaseHelper;
use common\helpers\ImageUrlHelper;
use common\models\constants\ConfigTypeConstant;
use common\models\constants\FileTypeConstant;
use common\models\constants\PostFilterTypeConstant;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use frontend\models\Post;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use frontend\models\Category;
use frontend\models\Reaction;
use yii\filters\AccessControl;
use frontend\models\PostSearch;
use yii\web\NotFoundHttpException;
use common\models\constants\StatusConstant;
use common\models\constants\PostStatusConstant;
use common\models\constants\ReactionTypeConstant;
use common\models\constants\ThreadTypeConstant;
use frontend\models\AppConfig;
use frontend\models\File;
use yii\base\NotSupportedException;
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
        $query = Post::find()->innerJoin('thread', 'post.threadId = thread.id')->where(['=', 'thread.type', ThreadTypeConstant::PUBLIC_THREAD])->andWhere(['=', 'post.status', 1])->andWhere(['post.parentId' => NULL])->orderBy(['post.created_at' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 6]);
        $posts = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages
        ]);
    }

    /**
     * Lists all Post models that satisfy the search condition.
     *
     * @return string
     */

    public function actionSearch($search)
    {
        $query = Post::find()->innerJoin('thread', 'post.threadId = thread.id')
            ->innerJoin('user', 'post.authorId = user.id')
            ->where(['=', 'thread.type', ThreadTypeConstant::PUBLIC_THREAD])
            ->andWhere(['=', 'post.status', 1])->andWhere(['post.parentId' => NULL])
            ->orderBy(['post.created_at' => SORT_DESC]);
        if ($search != "") {
            $query->andFilterWhere(['like', 'post.title', $search])
                ->orFilterWhere(['like', 'post.content', $search])
                ->orFilterWhere(['like', 'post.short_description', $search])
                ->orFilterWhere(['like', 'user.full_name', $search])
                ->orFilterWhere(['like', 'thread.name', $search]);
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 6]);
        $posts = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages
        ]);
    }

    /**
     * Displays a single Post model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $threadId = null, $notificationId = null)
    {
        if ($notificationId)
        {
            $this->updateNotificationStatus($notificationId);
        }
        $this->updateViewCount($id, 1);
        $comments = $this->getComments($id);
        $new_comment = new Post();
        return $this->render('view', [
            'model' => $this->findModel($id, $threadId),
            'new_comment' => $new_comment,
            'comments' => $comments,
            'threadId' => $threadId,
        ]);
    }

    private function updateNotificationStatus($notificationId)
    {
        $notification = Notification::find()->where(['=', 'id', $notificationId])->one();
        if ($notification)
        {
            $notification->isSeen = StatusConstant::NOTIFICAION_SEEN;
            $notification->save();
        }   
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($threadId = null)
    {
        $all_files = array();
        $all_files_preview = array();
        $all_cover_files = array();
        $all_cover_files_preview = array();
        $files_type = array();
        $model = new Post();
        $badWordConfig = AppConfig::find()->innerJoin('config_type', 'app_config.config_typeId = config_type.id')->where(['=', 'config_type.name', ConfigTypeConstant::BAD_WORDS])->andWhere(['=', 'app_config.status', StatusConstant::ACTIVE])->one();
        if ($badWordConfig)
        {
            $listBadWords = explode(', ', $badWordConfig->value);
        }
        else
        {
            $listBadWords = array();
        }
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($threadId) {
                    $model->threadId = $threadId;
                }
                $check = $model->save();
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
                if ($check) {
                    Yii::$app->session->setFlash('success', 'Create new post success');
                } else {
                    Yii::$app->session->setFlash('error', 'Some errors occur when create new post');
                }
                if ($threadId) {
                    return $this->redirect(['thread/view-group', 'threadId' => $threadId]);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        $categories = Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all();
        $isPrivate = $threadId != null ? true : false;

        return $this->render('create', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'all_cover_files' => $all_cover_files,
            'all_cover_files_preview' => $all_cover_files_preview,
            'files_type' => $files_type,
            'model' => $model,
            'isPrivate' => $isPrivate,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
            'listBadWords' => $listBadWords,
        ]);
    }

    public function actionComment($postId, $threadId = null)
    {
        $comment = new Post();
        if ($this->request->isPost) {
            if ($comment->load($this->request->post())) {
                $comment->title = "";
                $comment->threadId = NULL;
                $comment->is_approved = PostStatusConstant::APPROVED;
                $comment->short_description = '';
                if ($comment->save()) {
                    $this->updateReplyCount($postId, 1);
                    FirebaseHelper::sendNotifyWhenUpdatePost($postId, Yii::$app->user->identity->id, FirebaseHelper::ACTION_COMMENT, null, $comment->id);
                    Yii::$app->session->setFlash('success', 'Create new comment success');
                } else {
                    Yii::$app->session->setFlash('error', 'Some errors occur when create new comment');
                }
            }
        }
        if ($threadId) {
            return $this->redirect(['view', 'id' => $postId, 'threadId' => $threadId]);
        }
        return $this->redirect(['view', 'id' => $postId]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $badWordConfig = AppConfig::find()->innerJoin('config_type', 'app_config.config_typeId = config_type.id')->where(['=', 'config_type.name', ConfigTypeConstant::BAD_WORDS])->andWhere(['=', 'app_config.status', StatusConstant::ACTIVE])->one();
        if ($badWordConfig)
        {
            $listBadWords = explode(', ', $badWordConfig->value);
        }
        else
        {
            $listBadWords = array();
        }
        $all_files = array();
        $all_files_preview = array();
        $all_cover_files = array();
        $all_cover_files_preview = array();
        $files_type = array();
        $model = $this->findModel($id);
        $isPrivate = $model->thread->type == ThreadTypeConstant::PRIVATE_GROUP;
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

        if ($this->request->isPost && $model->load($this->request->post()) && $check = $model->save()) {

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
            if ($check) {
                Yii::$app->session->setFlash('success', 'Update post success');
            } else {
                Yii::$app->session->setFlash('error', 'Some errors occur when update post');
            }
            if ($isPrivate) {
                return $this->redirect(['thread/view-group', 'id' => $model->threadId]);
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
            'isPrivate' => $isPrivate,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
            'listBadWords' => $listBadWords,
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
    public function actionDelete($id, $parentId = null, $threadId = null)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->identity->id == $model->authorId) {
            $this->deleteFilesOfPost($id);
            $model->delete();
            if ($parentId && $threadId)
            {
                $parent = $this->findModel($parentId);
                $parent->updateCommentCount();
                return $this->redirect(['view', 'id' => $parentId, 'threadId' => $threadId]);
            }
            if ($parentId) {
                $parent = $this->findModel($parentId);
                $parent->updateCommentCount();
                return $this->redirect(['view', 'id' => $parentId]);
            }
            if ($threadId) {
                return $this->redirect(['thread/view-group', 'threadId' => $threadId]);
            }
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
    protected function findModel($id, $threadId = null)
    {
        if ($threadId) {
            $model = Post::findOne(['id' => $id, 'status' => StatusConstant::ACTIVE, 'threadId' => $threadId]);
        } else {
            $model = Post::findOne(['id' => $id, 'status' => StatusConstant::ACTIVE]);
        }
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    private function getComments($id)
    {
        return Post::find()->where(['=', 'parentId', $id])->with('subcomments')->all();
    }

    public function updateViewCount($postId, $value)
    {
        $post = Post::findOne(['id' => $postId]);
        if ($post) {
            $post->view_count += $value;
            $post->save();
        }
    }

    public function updateReplyCount($postId, $value)
    {
        $post = Post::findOne(['id' => $postId]);
        if ($post) {
            $post->reply_count += $value;
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $post->last_activity = date('Y-m-d H:i:s');
            $post->save();
        }
    }

    public function updatLikeCount($postId, $value)
    {
        $post = Post::findOne(['id' => $postId]);
        if ($post) {
            $post->like_count = $post->like_count + $value;
            if ($value > 0) {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $post->last_activity = date('Y-m-d H:i:s');
            }
            $post->save();
            if ($value > 0)
            {
                FirebaseHelper::sendNotifyWhenUpdatePost($postId, Yii::$app->user->identity->id, FirebaseHelper::ACTION_REACTION_LIKE);
            }
            return $post->like_count;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }



    public function actionUpdateLike()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($this->request->isPost) {
            $data = $this->request->post();
            $id = $data['id'];
            $isLiked = $data['isLiked'];
            $value = 0;
            $status = "";
            $error = "";
            if ($isLiked == "true") {
                $reaction = new Reaction();
                $reaction->userId = Yii::$app->user->identity->id;
                $reaction->postId = $id;
                $reaction->reaction_type = ReactionTypeConstant::REACTION_LIKE;
                if (!$reaction->save()) {
                    $status .= HttpCode::NOT_FOUND;
                } else {
                    $value = 1;
                }
            } else {
                $reaction = Reaction::find()->where(['userId' => Yii::$app->user->identity->id])->andWhere(['postId' => $id])->one();
                if ($reaction) {
                    $reaction->delete();
                    $value = -1;
                }
            }
            $status .= HttpCode::OK;
            return [
                'status' => $status,
                'data' => $this->updatLikeCount($id, $value)
            ];
        }
        throw new NotSupportedException(Yii::t('app', 'Method not supported.'));
    }

    public function actionUnlike($postId, $parentId = null)
    {
        $reaction = Reaction::find()->where(['userId' => Yii::$app->user->identity->id])->andWhere(['postId' => $postId])->one();
        if ($reaction) {
            if ($reaction->delete()) {
                $this->updatLikeCount($postId, -1, $parentId);
            }
        }
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

    public function actionGetPostsByThread($threadId)
    {
        $thread = Thread::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['=', 'id', $threadId])->one();
        $query = Post::find()->innerJoin('thread', 'post.threadId = thread.id')->where(['=', 'thread.type', ThreadTypeConstant::PUBLIC_THREAD])->andWhere(['=', 'post.status', 1])->andWhere(['post.parentId' => NULL])->andWhere(['=', 'thread.id', $threadId])->orderBy(['post.created_at' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 6]);
        $posts = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index_thread', [
            'thread' => $thread,
            'posts' => $posts,
            'pages' => $pages
        ]);
    }

    public function actionGetPosts($filterType, $threadId = null)
    {
        if ($threadId) {
            return $this->redirect(['thread/view-group', 'threadId' => $threadId, 'filterType' => $filterType]);
        }
        $query = Post::find()->innerJoin('thread', 'post.threadId = thread.id')->where(['=', 'thread.type', ThreadTypeConstant::PUBLIC_THREAD])->andWhere(['=', 'post.status', 1])->andWhere(['post.parentId' => NULL]);
        switch ($filterType) {
            case PostFilterTypeConstant::LASTEST_ACTIVITY_FILTER: {
                    $query = $query->orderBy(['last_activity' => SORT_DESC, 'post.created_at' => SORT_DESC]);
                    break;
                }
            case PostFilterTypeConstant::MOST_VIEW_POST_FILTER: {
                    $query->orderBy(['view_count' => SORT_DESC, 'post.created_at' => SORT_DESC]);
                    break;
                }
            case PostFilterTypeConstant::MOST_COMMENT_POST_FILTER: {
                    $query->orderBy(['reply_count' => SORT_DESC, 'post.created_at' => SORT_DESC]);
                    break;
                }
            case PostFilterTypeConstant::MOST_LIKE_POST_FILTER: {
                    $query->orderBy(['like_count' => SORT_DESC, 'post.created_at' => SORT_DESC]);
                    break;
                }
            default: {
                    $query->orderBy(['post.created_at' => SORT_DESC]);
                    break;
                }
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 6]);
        $posts = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages
        ]);
    }
}
