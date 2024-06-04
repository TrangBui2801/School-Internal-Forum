<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AccountPrivacyFixture extends ActiveFixture
{
    public $modelClass = 'common\models\AccountPrivacy';
    public $dataFile = '@tests/fixtures/data/account_privacy.php';
}