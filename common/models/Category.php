<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_description
 * @property string|null $description
 * @property int|null $status
 * @property string|null $image
 * @property int|null $moderatorId
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property User $moderator
 * @property Topic[] $topics
 */
class Category extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'image'], 'string'],
            [['name'], 'unique'],
            [['status', 'moderatorId', 'created_by', 'updated_by'], 'integer'],
            [['name', 'short_description', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['moderatorId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['moderatorId' => 'id']],
            [['file'], 'file', 'maxFiles' => 0]
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
            'short_description' => Yii::t('app', 'Short Description'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'image' => Yii::t('app', 'Image'),
            'moderatorId' => Yii::t('app', 'Moderator ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Moderator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModerator()
    {
        return $this->hasOne(User::class, ['id' => 'moderatorId']);
    }

    /**
     * Gets query for [[Topics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topic::class, ['categoryId' => 'id']);
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
