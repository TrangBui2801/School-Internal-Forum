<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class FileFixture extends ActiveFixture
{
    public $modelClass = 'common\models\File';
    public $dataFile = '@tests/fixtures/data/file.php';
}