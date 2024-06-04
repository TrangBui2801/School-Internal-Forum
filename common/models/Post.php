<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string|null $short_description
 * @property int $threadId
 * @property int|null $parentId
 * @property int|null $level
 * @property int $authorId
 * @property int|null $view_count
 * @property int|null $reply_count
 * @property int|null $like_count
 * @property int|null $tagged_userId
 * @property string|null $last_activity
 * @property int|null $is_approved
 * @property int|null $status
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property User $author
 * @property File[] $files
 * @property Post $parent
 * @property Post[] $posts
 * @property Reaction[] $reactions
 * @property Report[] $reports
 * @property User $taggedUser
 * @property Thread $thread
 */
class Post extends \yii\db\ActiveRecord
{
    public $topicId;
    public $categoryId;
    public $attachment;
    public $cover_image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['title', 'content'], 'string'],
            [['threadId', 'parentId', 'authorId', 'view_count', 'reply_count', 'like_count', 'tagged_userId', 'is_approved', 'status', 'created_by', 'updated_by', 'level'], 'integer'],
            [['short_description', 'last_activity', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['authorId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['authorId' => 'id']],
            [['parentId'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['parentId' => 'id']],
            [['tagged_userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['tagged_userId' => 'id']],
            [['threadId'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::class, 'targetAttribute' => ['threadId' => 'id']],
            [['attachment', 'cover_image'], 'file', 'maxFiles' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'short_description' => Yii::t('app', 'Short Description'),
            'threadId' => Yii::t('app', 'Thread'),
            'parentId' => Yii::t('app', 'Parent'),
            'level' => Yii::t('app', 'Level'),
            'authorId' => Yii::t('app', 'Author'),
            'view_count' => Yii::t('app', 'View Count'),
            'reply_count' => Yii::t('app', 'Reply Count'),
            'like_count' => Yii::t('app', 'Like Count'),
            'cover_image' => Yii::t('app', 'Cover Image'),
            'attachment' => Yii::t('app', 'Attachment'),
            'tagged_userId' => Yii::t('app', 'Tagged User'),
            'last_activity' => Yii::t('app', 'Last Activity'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'authorId']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['parentId' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Post::class, ['id' => 'parentId']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Post::class, ['parentId' => 'id']);
    }

    public function getSubcomments()
    {
        return $this->hasMany(Post::class, ['parentId' => 'id'])->inverseOf('comments');
    }


    /**
     * Gets query for [[Reactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReactions()
    {
        return $this->hasMany(Reaction::class, ['postId' => 'id']);
    }

    /**
     * Gets query for [[Reports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['postId' => 'id']);
    }

    /**
     * Gets query for [[TaggedUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaggedUser()
    {
        return $this->hasOne(User::class, ['id' => 'tagged_userId']);
    }

    /**
     * Gets query for [[Thread]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(Thread::class, ['id' => 'threadId']);
    }

    public function beforeDelete()
    {
        $count = 0;
        $children = $this->comments;
        if ($children) {
            $count += count($children);
            foreach ($children as $comment) {
                $subChildren = $comment->comments;
                $count += count($subChildren);
            }
        }
        if ($this->parentId) {
            $parent = Post::find()->where(['=', 'id', $this->parentId])->one();
            $parent->reply_count = $parent->reply_count - ($count + 1);
            $parent->save();
        }
        Reaction::deleteAll(['=', 'postId', $this->id]);
        return parent::beforeDelete();
    }

    public function updateCommentCount()
    {
        $count = 0;
        $children = $this->comments;
        if ($children) {
            $count += count($children);
            foreach ($children as $child) {
                $subChildren = $child->comments;
                $count += count($subChildren);
            }
        }
        $this->reply_count = $count;
    }
}
