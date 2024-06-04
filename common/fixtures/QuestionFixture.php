<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class QuestionFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Question';
    public $dataFile = '@tests/fixtures/data/question.php';
}