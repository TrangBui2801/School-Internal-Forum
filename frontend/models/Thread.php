<?php

namespace frontend\models;

use common\models\constants\ThreadTypeConstant;
use common\models\Thread as ModelsThread;
use Yii;

class Thread extends ModelsThread
{
    public static function getPrivateGroup()
    {
        $private_group = Thread::find()->innerJoin('group_member', 'thread.id  = group_member.groupId')->where(['=', 'thread.type', ThreadTypeConstant::PRIVATE_GROUP])->andWhere(['OR', ['thread.moderatorId' => Yii::$app->user->identity->id], ['group_member.memberId' => Yii::$app->user->identity->id]])->all();
        return $private_group;
    }
}
