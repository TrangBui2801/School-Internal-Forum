<?php

use common\models\constants\StatusConstant;

return [
    [
        'id' => '1',
        'name' => "Beginner",
        'description' => "Question for beginner level",
        'time' => "30",
        'short_description' => "Question for beginner level",
        'status' => StatusConstant::ACTIVE,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
    [
        'id' => '2',
        'name' => "Intermediate",
        'description' => "Question for intermediate level",
        'time' => "40",
        'short_description' => "Question for intermediate level",
        'status' => StatusConstant::ACTIVE,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
    [
        'id' => '3',
        'name' => "Advanced",
        'description' => "Question for advanced level",
        'time' => "30",
        'short_description' => "Question for advanced level",
        'status' => StatusConstant::ACTIVE,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
];