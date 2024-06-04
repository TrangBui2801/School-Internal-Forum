<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class TopicFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Topic';
    public $dataFile = '@tests/fixtures/data/topic.php';
}