<?php

use common\models\constants\StatusConstant;

return [
    [
        'id' => '1',
        'name' => 'APP_BADWORD',
        'status' => StatusConstant::ACTIVE,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => 1,
        'updated_at' => null,
        'updated_by' => null,
    ],
];