<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class GroupMemberFixture extends ActiveFixture
{
    public $modelClass = 'common\models\GroupMember';
    public $dataFile = '@tests/fixtures/data/group_member.php';
}