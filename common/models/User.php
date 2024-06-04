<?php

namespace common\models;

use common\models\constants\StatusConstant;
use common\models\constants\UserRoleConstant;
use common\models\constants\UserStatusConstant;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $_username
 * @property string $_password
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $address
 * @property string|null $phone_number
 * @property string|null $birthday
 * @property string|null $avatar
 * @property string|null $gender
 * @property string|null $introduction
 * @property string|null $short_introduction
 * @property string|null $facebook_link
 * @property string|null $skype_link
 * @property string|null $github_link
 * @property string|null $youtube_link
 * @property int|null $departmentId
 * @property int|null $role
 * @property int|null $status
 * @property string $auth_key
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property AccountPrivacy[] $accountPrivacies
 * @property Category[] $categories
 * @property Department $department
 * @property EventAttendance[] $eventAttendances
 * @property GroupMember[] $groupMembers
 * @property GroupMember[] $groupMembers0
 * @property Post[] $posts
 * @property Post[] $posts0
 * @property Reaction[] $reactions
 * @property Report[] $reports
 * @property Test[] $tests
 * @property Thread[] $threads
 * @property Topic[] $topics
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_username', '_password'], 'required'],
            [['_password', 'address', 'avatar', 'introduction', 'facebook_link', 'skype_link', 'github_link', 'youtube_link'], 'string'],
            [['departmentId', 'role', 'status', 'created_by', 'updated_by'], 'integer'],
            [['_username', 'full_name', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['phone_number', 'short_introduction'], 'string', 'max' => 20],
            [['birthday', 'gender'], 'string', 'max' => 10],
            [['auth_key'], 'string', 'max' => 32],
            [['_username', 'email'], 'unique'],
            [['departmentId'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['departmentId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            '_username' => Yii::t('app', 'Username'),
            '_password' => Yii::t('app', 'Password'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'birthday' => Yii::t('app', 'Birthday'),
            'avatar' => Yii::t('app', 'Avatar'),
            'gender' => Yii::t('app', 'Gender'),
            'introduction' => Yii::t('app', 'Introduction'),
            'short_introduction' => Yii::t('app', 'Short Introduction'),
            'facebook_link' => Yii::t('app', 'Facebook Link'),
            'skype_link' => Yii::t('app', 'Skype Link'),
            'github_link' => Yii::t('app', 'Github Link'),
            'youtube_link' => Yii::t('app', 'Youtube Link'),
            'departmentId' => Yii::t('app', 'Department ID'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[AccountPrivacies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPrivacies()
    {
        return $this->hasMany(AccountPrivacy::class, ['userId' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['moderatorId' => 'id']);
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'departmentId']);
    }

    /**
     * Gets query for [[EventAttendances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventAttendances()
    {
        return $this->hasMany(EventAttendance::class, ['userId' => 'id']);
    }

    public function getNotifications()
    {
        return $this->hasMany(Notification::class, ['userId' => 'id']);
    }

    public function getNotifications1()
    {
        return $this->hasMany(Notification::class, ['actorId' => 'id']);
    }

    /**
     * Gets query for [[GroupMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupMembers()
    {
        return $this->hasMany(GroupMember::class, ['creatorId' => 'id']);
    }

    /**
     * Gets query for [[GroupMembers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupMembers0()
    {
        return $this->hasMany(GroupMember::class, ['memberId' => 'id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['authorId' => 'id', 'parentId' => null]);
    }

    /**
     * Gets query for [[Posts0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts0()
    {
        return $this->hasMany(Post::class, ['tagged_userId' => 'id']);
    }

    /**
     * Gets query for [[Reactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReactions()
    {
        return $this->hasMany(Reaction::class, ['userId' => 'id']);
    }

    /**
     * Gets query for [[Reports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['userId' => 'id']);
    }

    /**
     * Gets query for [[Tests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTests()
    {
        return $this->hasMany(Test::class, ['userId' => 'id']);
    }

    /**
     * Gets query for [[Threads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThreads()
    {
        return $this->hasMany(Thread::class, ['moderatorId' => 'id']);
    }

    /**
     * Gets query for [[Topics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topic::class, ['moderatorId' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => UserStatusConstant::ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->_password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword()
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public static function findByUsername($username)
    {
        return static::findOne(['_username' => $username, 'status' => UserStatusConstant::ACTIVE]);
    }

    public static function isUserAdmin($username)
    {
        if (static::findOne(['_username' => $username, 'role' => UserRoleConstant::ADMIN, 'status' => StatusConstant::ACTIVE])) {
            return true;
        } else {
            Yii::$app->session->setFlash('error', 'You do not have permission to access this page');
            return false;
        }
    }
}
