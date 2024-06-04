<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ThreadFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Thread';
    public $dataFile = '@tests/fixtures/data/thread.php';
}