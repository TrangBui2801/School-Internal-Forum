<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class MenuFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Menu';
    public $dataFile = '@tests/fixtures/data/menu.php';
}