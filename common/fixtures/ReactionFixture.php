<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ReactionFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Reaction';
    public $dataFile = '@tests/fixtures/data/reaction.php';
}