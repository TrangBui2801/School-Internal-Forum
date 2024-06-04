<?php

namespace backend\models;

use common\models\constants\PostStatusConstant;
use common\models\constants\StatusConstant;
use common\models\Post as ModelsPost;
use Yii;

class Post extends ModelsPost
{
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = new \yii\db\Expression('NOW()');
            $this->created_by = Yii::$app->user->identity->id;
            $this->parentId = NULL;
            $this->view_count = 0;
            $this->reply_count = 0;
            $this->like_count = 0;
            $this->tagged_userId = NULL;
            $this->is_approved = PostStatusConstant::APPROVED;
            $this->status = StatusConstant::ACTIVE;
        } else {
            $this->updated_at = new \yii\db\Expression('NOW()');
            $this->updated_by = Yii::$app->user->identity->id;
        }
        $this->authorId = Yii::$app->user->identity->id;
        $this->last_activity = new \yii\db\Expression('NOW()');
        return parent::beforeSave($insert);
    }
}
