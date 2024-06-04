<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%config_type}}".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property AppConfig[] $appConfigs
 */
class ConfigType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[AppConfigs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppConfigs()
    {
        return $this->hasMany(AppConfig::class, ['config_typeId' => 'id']);
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {  
            $this->created_at = new \yii\db\Expression('NOW()');  
            $this->created_by = Yii::$app->user->identity->id;
        } else {  
            $this->updated_at = new \yii\db\Expression('NOW()');  
            $this->updated_by = Yii::$app->user->identity->id;  
        }  
        return parent::beforeSave($insert);  
    }
}
