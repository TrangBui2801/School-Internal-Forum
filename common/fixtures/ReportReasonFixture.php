<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ReportReasonFixture extends ActiveFixture
{
    public $modelClass = 'common\models\ReportReason';
    public $dataFile = '@tests/fixtures/data/report_reason.php';
}