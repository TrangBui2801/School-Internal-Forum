<?php

namespace common\helpers;

use common\models\constants\StatusConstant;
use common\models\FirebaseMessage;
use common\models\FirebaseToken;
use common\models\Notification;
use common\models\Thread;
use frontend\models\Post;
use frontend\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class FirebaseHelper
{
    public const ACTION_POST = 0;
    public const ACTION_COMMENT = 1;
    public const ACTION_REACTION_LIKE = 2;
    public const ACTION_MENTION = 3;

    private $url = 'https://fcm.googleapis.com/fcm/send';
    private $serverKey = 'AAAAgjSB-DE:APA91bERmUdc8V1jm2aS9xNt3xQIKDne1704C8a8-uBf_6HcEMQ-cWO_0I0w6t8p_n2cYCvTPtoZ-vPw3NBEhs3DQ_zqiMul-AcjnlKlTP8RIt4fBpAlmeblmfyXy_Lw2qWqsPcnl35b';
    private function sendNotification($message, $tokens)
    {
        $notification = array(
            'registration_ids' => $tokens,
            'notification' => array(
                'title' => $message->title,
                'body' => $message->body,
                'sound' => $message->sound,
                'icon' => $message->icon
            ),
            'data' => array(
                'dataId' => $message->dataId,
                'dataType' => $message->dataType,
            )
        );
        $headers = array(
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json',
        );
        $data = json_encode($notification);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
    }

    public static function sendNotifyWhenUpdatePost($postId, $actorId, $action, $threadId = null, $actorObjectId = null) {
        $post = Post::find()->where(['=', 'id', $postId])->andWhere(['=', 'status', StatusConstant::ACTIVE])->one();
        $actor = User::find()->where((['=', 'id', $actorId]))->one();
        if ($post)
        {
            $message = new FirebaseMessage();
            $title = "";
            $body = "";
            if ($action == FirebaseHelper::ACTION_COMMENT)
            {
                $title = $actor->full_name . " commented on your post: " . $post->title;
                $actorObject = Post::find()->where(['=', 'id', $actorObjectId])->andWhere(['=', 'status', StatusConstant::ACTIVE])->one();
                $body = $actorObject->content;
            }
            else if ($action == FirebaseHelper::ACTION_REACTION_LIKE)
            {
                if ($post->parentId == null)
                {
                    $title = $actor->full_name . " liked your post: " . $post->title;
                }
                else
                {
                    $title = $actor->full_name . " liked your comment: " . $post->content;
                }
            }
            else if ($action == FirebaseHelper::ACTION_MENTION)
            {
                $title = $actor->full_name . " mentioned you in a comment";
            }
            else if ($action == FirebaseHelper::ACTION_POST && $threadId) {
                $thread = Thread::find()->where(['=', 'id', $threadId])->one();
                $title = $actor->full_name . " posted in your group " . $thread->name;
                $body = $post->title;
            }
            $message->title = $title;
            $message->body = $body;
            $message->sound = "default";
            $helper = new FirebaseHelper();
            $tokenList = FirebaseToken::find()->where(['=', 'userId', $post->authorId])->asArray()->all();
            $tokens = ArrayHelper::getColumn($tokenList, 'deviceToken');
            $helper->sendNotification($message, $tokens);
            $newNotification = new Notification();
            $newNotification->parentId = $postId;
            $newNotification->userId = $post->authorId;
            $newNotification->actorId = $actorId;
            $newNotification->content = $title;
            $newNotification->isSeen = StatusConstant::NOTIFICATION_NOT_SEEN;
            if ($threadId)
            {
                $newNotification->url = Url::to(['/post/view', 'id' => $postId, 'threadId' => $threadId]);
            }
            else
            {
                $newNotification->url = Url::to(['/post/view', 'id' => $postId]);
            }
            $newNotification->save();
        }
    }
}
