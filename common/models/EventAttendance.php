<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%event_attendance}}".
 *
 * @property int $id
 * @property int|null $userId
 * @property int|null $eventId
 * @property int|null $status
 * @property string $created_at
 * @property int|null $priority
 *
 * @property Event $event
 * @property User $user
 */
class EventAttendance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event_attendance}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'eventId', 'status', 'priority'], 'integer'],
            [['created_at'], 'string', 'max' => 255],
            [['eventId'], 'exist', 'skipOnError' => true, 'targetClass' => Event::class, 'targetAttribute' => ['eventId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'eventId' => Yii::t('app', 'Event ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'priority' => Yii::t('app', 'Priority'),
        ];
    }

    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::class, ['id' => 'eventId']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {  
            $this->created_at = new \yii\db\Expression('NOW()');
        }
        return parent::beforeSave($insert);  
    }
}
