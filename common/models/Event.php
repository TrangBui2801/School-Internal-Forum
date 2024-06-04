<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%event}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string $start_date
 * @property string|null $description
 * @property string|null $short_description
 * @property int|null $status
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property EventAttendance[] $eventAttendances
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_date'], 'required'],
            [['description', 'short_description'], 'string'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['name', 'start_date', 'created_at', 'updated_at'], 'string', 'max' => 255],
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
            'start_date' => Yii::t('app', 'Start Date'),
            'description' => Yii::t('app', 'Description'),
            'short_description' => Yii::t('app', 'Short Description'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[EventAttendances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventAttendances()
    {
        return $this->hasMany(EventAttendance::class, ['eventId' => 'id']);
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
