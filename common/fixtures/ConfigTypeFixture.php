<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ConfigTypeFixture extends ActiveFixture
{
    public $modelClass = 'backend\models\ConfigType';
    public $dataFile = '@tests/fixtures/data/config_type.php';
}