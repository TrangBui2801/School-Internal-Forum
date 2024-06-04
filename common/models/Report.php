<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%report}}".
 *
 * @property int $id
 * @property int|null $userId
 * @property int|null $postId
 * @property int|null $reasonId
 * @property int|null $status
 * @property string $created_at
 * @property int|null $approved_by
 *
 * @property Post $post
 * @property ReportReason $reason
 * @property User $user
 */
class Report extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%report}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'postId', 'reasonId', 'status', 'approved_by'], 'integer'],
            [['created_at'], 'string', 'max' => 255],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['postId' => 'id']],
            [['reasonId'], 'exist', 'skipOnError' => true, 'targetClass' => ReportReason::class, 'targetAttribute' => ['reasonId' => 'id']],
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
            'reasonId' => Yii::t('app', 'Reason ID'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'approved_by' => Yii::t('app', 'Approved By'),
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
     * Gets query for [[Reason]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReason()
    {
        return $this->hasOne(ReportReason::class, ['id' => 'reasonId']);
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
