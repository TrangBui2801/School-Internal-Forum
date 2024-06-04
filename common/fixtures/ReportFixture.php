<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ReportFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Report';
    public $dataFile = '@tests/fixtures/data/report.php';
}