<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class EventFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Event';
    public $dataFile = '@tests/fixtures/data/event.php';
}