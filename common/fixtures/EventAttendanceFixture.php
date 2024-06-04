<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class EventAttendanceFixture extends ActiveFixture
{
    public $modelClass = 'common\models\EventAttendance';
    public $dataFile = '@tests/fixtures/data/event_attendance.php';
}