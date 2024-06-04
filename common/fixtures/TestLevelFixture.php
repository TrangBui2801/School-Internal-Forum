<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class TestLevelFixture extends ActiveFixture
{
    public $modelClass = 'common\models\TestLevel';
    public $dataFile = '@tests/fixtures/data/test_level.php';
}