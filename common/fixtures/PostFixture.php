<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PostFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Post';
    public $dataFile = '@tests/fixtures/data/post.php';
}