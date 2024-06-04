<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class NotificationFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Notification';
    public $dataFile = '@tests/fixtures/data/notification.php';
}