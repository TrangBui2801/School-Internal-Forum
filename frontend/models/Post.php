<?php

namespace frontend\models;

use common\models\constants\PostStatusConstant;
use common\models\constants\StatusConstant;
use common\models\constants\ThreadTypeConstant;
use common\models\Post as ModelsPost;
use Yii;

class Post extends ModelsPost
{
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = new \yii\db\Expression('NOW()');
            $this->created_by = Yii::$app->user->identity->id;
            $this->view_count = 0;
            $this->reply_count = 0;
            $this->like_count = 0;
            $this->tagged_userId = NULL;
            $this->status = StatusConstant::ACTIVE;
            $this->authorId = Yii::$app->user->identity->id;
        } else {
            $this->updated_at = new \yii\db\Expression('NOW()');
            $this->updated_by = Yii::$app->user->identity->id;
        }
        
        
        return parent::beforeSave($insert);
    }

    public static function getLastestPost()
    {
        $lastestPost = Post::find()->innerJoin('thread', 'post.threadId = thread.id')->where(['=', 'thread.type', ThreadTypeConstant::PUBLIC_THREAD])->andWhere(['=', 'post.status', 1])->andWhere(['post.parentId' => NULL])->orderBy(['post.created_at' => SORT_DESC])->limit(6)->all();
        return $lastestPost;
    }
}
