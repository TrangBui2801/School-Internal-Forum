<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class DepartmentFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Department';
    public $dataFile = '@tests/fixtures/data/department.php';
}