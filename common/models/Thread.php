<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%thread}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_description
 * @property string|null $description
 * @property int|null $topicId
 * @property int|null $status
 * @property int|null $type
 * @property string|null $image
 * @property int|null $moderatorId
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property GroupMember[] $groupMembers
 * @property User $moderator
 * @property Post[] $posts
 * @property Question[] $questions
 * @property Test[] $tests
 * @property Topic $topic
 */
class Thread extends \yii\db\ActiveRecord
{
    public $memberId;
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%thread}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'image', 'sDescription'], 'string'],
            [['memberId'], 'each', 'rule' => ['string']],
            [['name'], 'unique'],
            [['topicId', 'status', 'type', 'moderatorId', 'created_by', 'updated_by'], 'integer'],
            [['name', 'short_description', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['moderatorId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['moderatorId' => 'id']],
            [['topicId'], 'exist', 'skipOnError' => true, 'targetClass' => Topic::class, 'targetAttribute' => ['topicId' => 'id']],
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
            'sDescription' => Yii::t('app', 'sDescription'),
            'topicId' => Yii::t('app', 'Topic ID'),
            'status' => Yii::t('app', 'Status'),
            'type' => Yii::t('app', 'Type'),
            'image' => Yii::t('app', 'Image'),
            'memberId' => Yii::t('app', 'Member ID'),
            'moderatorId' => Yii::t('app', 'Moderator'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[GroupMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupMembers()
    {
        return $this->hasMany(GroupMember::class, ['groupId' => 'id']);
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
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['threadId' => 'id']);
    }

    /**
     * Gets query for [[Questions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['threadId' => 'id']);
    }

    /**
     * Gets query for [[Tests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTests()
    {
        return $this->hasMany(Test::class, ['threadId' => 'id']);
    }

    /**
     * Gets query for [[Topic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::class, ['id' => 'topicId']);
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

    public static function getThreadByTopicId($topicId)
    {
        $thread = Thread::find()->select(['id', 'name'])->where(['=', 'topicId', $topicId])->andWhere(['=', 'status', StatusConstant::ACTIVE])->asArray()->all();
        return $thread;
    }

    public function beforeDelete()
    {
        Post::deleteAll(['=', 'threadId', $this->id]);
        return parent::beforeDelete();
    }
}
