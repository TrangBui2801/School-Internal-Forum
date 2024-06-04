<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class FirebaseTokenFixture extends ActiveFixture
{
    public $modelClass = 'common\models\FirebaseToken';
    public $dataFile = '@tests/fixtures/data/firebase_token.php';
}