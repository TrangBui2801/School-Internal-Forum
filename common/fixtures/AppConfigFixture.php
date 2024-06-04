<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AppConfigFixture extends ActiveFixture
{
    public $modelClass = 'backend\models\AppConfig';
    public $dataFile = '@tests/fixtures/data/app_config.php';
}