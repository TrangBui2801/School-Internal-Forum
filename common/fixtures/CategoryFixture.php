<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class CategoryFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Category';
    public $dataFile = '@tests/fixtures/data/category.php';
}