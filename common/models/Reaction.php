<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%reaction}}".
 *
 * @property int $id
 * @property int|null $userId
 * @property int|null $postId
 * @property int|null $reaction_type
 * @property string $created_at
 *
 * @property Post $post
 * @property User $user
 */
class Reaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'postId', 'reaction_type'], 'integer'],
            [['created_at'], 'string', 'max' => 255],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['postId' => 'id']],
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
            'postId' => Yii::t('app', 'Post ID'),
            'reaction_type' => Yii::t('app', 'Reaction Type'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'postId']);
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
