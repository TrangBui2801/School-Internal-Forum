<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%app_config}}".
 *
 * @property int $id
 * @property int|null $config_typeId
 * @property string|null $value
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property ConfigType $configType
 */
class AppConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%app_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['config_typeId', 'status', 'created_by', 'updated_by'], 'integer'],
            [['value'], 'string'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['config_typeId'], 'exist', 'skipOnError' => true, 'targetClass' => ConfigType::class, 'targetAttribute' => ['config_typeId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_typeId' => Yii::t('app', 'Config Type ID'),
            'value' => Yii::t('app', 'Value'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[ConfigType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfigType()
    {
        return $this->hasOne(ConfigType::class, ['id' => 'config_typeId']);
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
