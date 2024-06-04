<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class TestFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Test';
    public $dataFile = '@tests/fixtures/data/test.php';
}