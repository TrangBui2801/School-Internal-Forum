<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class TestDetailFixture extends ActiveFixture
{
    public $modelClass = 'common\models\TestDetail';
    public $dataFile = '@tests/fixtures/data/test_detail.php';
}