<?php

use common\models\constants\StatusConstant;

return [
    [
        'id' => '1',
        'name' => 'Administrator',
        'description' => 'Department for users who manage the overall system',
        'short_description' => 'Administrator department',
        'phone_number' => '0941900193',
        'status' => StatusConstant::ACTIVE,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
    [
        'id' => '2',
        'name' => 'Student admissions',
        'description' => 'Student admissions department',
        'short_description' => 'Department for users is responsible for admissions to the school',
        'phone_number' => '0941900194',
        'status' => StatusConstant::ACTIVE,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
    [
        'id' => '3',
        'name' => 'Trainning department',
        'description' => 'Trainning department',
        'short_description' => 'Department for users is responsible for student training of the school',
        'phone_number' => '0941900194',
        'status' => StatusConstant::ACTIVE,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
    [
        'id' => '4',
        'name' => 'Technical Department',
        'description' => 'Technical Department',
        'short_description' => 'Department for users is responsible for technical area of the school',
        'phone_number' => '0941900194',
        'status' => StatusConstant::ACTIVE,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
];