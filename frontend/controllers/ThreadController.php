<?php

namespace frontend\controllers;

use common\models\constants\PostFilterTypeConstant;
use Yii;
use yii\web\Controller;
use frontend\models\Thread;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\ThreadSearch;
use yii\web\NotFoundHttpException;
use common\models\constants\StatusConstant;
use common\models\constants\ThreadTypeConstant;
use frontend\models\GroupMember;
use frontend\models\Post;
use frontend\models\Topic;
use frontend\models\User;
use yii\data\Pagination;
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
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionViewGroup($threadId, $filterType = null)
    {
        $model = $this->findPrivateGroup($threadId);
        $userInGroupIDs = GroupMember::find()->select(['memberId'])->where(['=', 'groupId', $threadId])->asArray()->all();
        $excludeIds = array();
        foreach ($userInGroupIDs as $userId) {
            array_push($excludeIds, $userId['memberId']);
        }
        if (in_array(Yii::$app->user->identity->id, $excludeIds)) {
            $userNotInGroup = User::find()->where(['NOT IN', 'id', $excludeIds])->andWhere(['=', 'user.status', StatusConstant::ACTIVE])->all();
            $query = Post::find()->where(['=', 'status', StatusConstant::ACTIVE])->andWhere(['=', 'threadId', $threadId])->andWhere(['parentId' => null])->orderBy(['created_at' => SORT_DESC]);
            if ($filterType) {
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
            }
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 6]);
            $posts = $query->offset($pages->offset)->limit($pages->limit)->all();
            return $this->render('view', [
                'model' => $model,
                'posts' => $posts,
                'pages' => $pages,
                'userNotInGroup' => ArrayHelper::map($userNotInGroup, 'id', 'full_name'),
            ]);
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionRemoveUserFromGroup($groupId, $memberId)
    {
        if (Yii::$app->user->identity->id != $memberId) {
            $check = GroupMember::find()->where(['=', 'memberId', $memberId])->andWhere(['=', 'groupId', $groupId])->one();
            if ($check) {
                $check->delete();
            }
        }
        return $this->redirect(['view-group', 'threadId' => $groupId]);
    }

    public function actionAddUsersToGroup($id)
    {
        $userIds = $this->request->post()['Thread']['memberId'];
        if ($userIds)
        {
            foreach ($userIds as $userId) {
                $check = GroupMember::find()->where(['=', 'memberId', $userId])->andWhere(['=', 'groupId', $id])->one();
                if (!$check) {
                    $groupMember = new GroupMember();
                    $groupMember->groupId = $id;
                    $groupMember->memberId = $userId;
                    $groupMember->creatorId = Yii::$app->user->identity->id;
                    $groupMember->save();
                }
            }
            Yii::$app->session->setFlash('success', 'Add user(s) to group success');
        }
        else
        {
            Yii::$app->session->setFlash('error', 'An error occur when add user(s) to group');
        }
        return $this->redirect(['view-group', 'threadId' => $id]);
    }

    public function actionCreatePrivateGroup()
    {
        $group = new Thread();
        $all_files = array();
        $all_files_preview = array();
        $files_type = array();

        if ($this->request->isPost) {
            if ($group->load($this->request->post())) {
                $folder_name = "thread_" . $group->name;
                $files = UploadedFile::getInstances($group, 'image');
                FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/threads/' . $folder_name, $mode = 0775, $recursive = true);
                if ($files)
                {
                    foreach ($files as $file) {
                        $url = Url::to('@backend') . '/web/uploads/threads/' . $folder_name . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                        $file->saveAs($url);
                        $group->image = $url;
                    }
                }
                else {
                    $group->image = Url::to('@backend') . '/web/uploads/threads/thread_no_image/no-image.png';
                }
                $group->type = ThreadTypeConstant::PRIVATE_GROUP;
                $group->moderatorId = Yii::$app->user->identity->id;
                $group->save();
                $groupMember = new GroupMember();
                $groupMember->groupId = $group->id;
                $groupMember->memberId = Yii::$app->user->identity->id;
                $groupMember->creatorId = Yii::$app->user->identity->id;
                $groupMember->save();
                return $this->redirect(['view-group', 'threadId' => $group->id]);
            }
        } else {
            $group->loadDefaultValues();
        }

        return $this->render('create-group', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'files_type' => $files_type,
            'model' => $group,
        ]);
    }

    public function actionUpdatePrivateGroup()
    {
        $group = new Thread();
        $all_files = array();
        $all_files_preview = array();
        $files_type = array();

        if ($group->image) {
            $folder_url = substr($group->image, 0, strripos($group->image, '/'));
            $all_files[] = Url::base(TRUE) . "/" . $group->image;
            $obj = (object) array('caption' => $group->name . " avatar", 'url' => "'" . Url::to(['file/delete-file', 'id' => $group->id]) . "'", 'key' => $group->id, 'type' => "image");
            $files_type[] = "image";
            $all_files_preview[] = $obj;
        }

        if ($this->request->isPost && $group->load($this->request->post())) {
            $files = UploadedFile::getInstances($group, 'image');
            if ($files) {
                foreach ($files as $file) {
                    if ($folder_url != '' && file_exists($folder_url)) {
                        $url = $folder_url . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                    } else {
                        $folder_name = "thread_" . $group->name;
                        FileHelper::createDirectory(Url::to('@backend') . '/web/uploads/threads/' . $folder_name, $mode = 0775, $recursive = true);
                        $url = Url::to('@backend') . '/web/uploads/threads/' . $folder_name . '/' . Yii::$app->security->generateRandomString(12) . '.' . $file->extension;
                        $group->image = $url;
                    }
                }
            }
            $group->save();

            return $this->redirect(['view-group', 'threadId' => $group->id]);
        }

        return $this->render('update-group', [
            'all_files' => $all_files,
            'all_files_preview' => $all_files_preview,
            'files_type' => $files_type,
            'model' => $group,
        ]);
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

    protected function findPrivateGroup($id)
    {
        $model = Thread::find()->where(['id' => $id])->andWhere(['type' => ThreadTypeConstant::PRIVATE_GROUP])->one();
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetThreadsByTopic($topicId)
    {
        $topic = Topic::find()->where(['=', 'id', $topicId])->andWhere(['=', 'status', StatusConstant::ACTIVE])->one();
        if ($topic) {
            return $this->render('index', [
                'topic' => $topic
            ]);
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
