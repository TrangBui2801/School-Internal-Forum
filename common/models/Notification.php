<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property int $id
 * @property int|null $parentId
 * @property int|null $userId
 * @property int|null $actorId
 * @property string|null $content
 * @property int|null $status
 * @property int|null $isSeen
 * @property string|null $url
 * @property int|null $type
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parentId', 'userId', 'actorId', 'status', 'isSeen', 'type', 'created_by', 'updated_by'], 'integer'],
            [['url', 'content', 'created_at', 'updated_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parentId' => Yii::t('app', 'Parent ID'),
            'userId' => Yii::t('app', 'User ID'),
            'actorId' => Yii::t('app', 'Actor ID'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'isSeen' => Yii::t('app', 'Is Seen'),
            'url' => Yii::t('app', 'Url'),
            'type' => Yii::t('app', 'Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {  
            $this->created_at = new \yii\db\Expression('NOW()');  
            $this->created_by = Yii::$app->user->identity->id;
            $this->status = StatusConstant::ACTIVE;
        } else {  
            $this->updated_at = new \yii\db\Expression('NOW()');  
            $this->updated_by = Yii::$app->user->identity->id;  
        }  
        return parent::beforeSave($insert);  
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    public function getActor()
    {
        return $this->hasOne(User::class, ['id' => 'actorId']);
    }
}
