<?php

namespace backend\models;

use common\models\AppConfig;
use Yii;

class BadWord extends AppConfig
{

    public function rules()
    {
        return [
            [['config_typeId', 'status', 'created_by', 'updated_by'], 'integer'],
            [['value'], 'string'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['config_typeId'], 'exist', 'skipOnError' => true, 'targetClass' => ConfigType::class, 'targetAttribute' => ['config_typeId' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_typeId' => Yii::t('app', 'Config Type'),
            'value' => Yii::t('app', 'List Bad Words'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}