<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%group_member}}".
 *
 * @property int $id
 * @property int|null $groupId
 * @property int|null $memberId
 * @property int|null $status
 * @property int|null $creatorId
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property User $creator
 * @property Thread $group
 * @property User $member
 */
class GroupMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%group_member}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['groupId', 'memberId', 'status', 'creatorId', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['creatorId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creatorId' => 'id']],
            [['groupId'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::class, 'targetAttribute' => ['groupId' => 'id']],
            [['memberId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['memberId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'groupId' => Yii::t('app', 'Group ID'),
            'memberId' => Yii::t('app', 'Member ID'),
            'status' => Yii::t('app', 'Status'),
            'creatorId' => Yii::t('app', 'Creator ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Creator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'creatorId']);
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Thread::class, ['id' => 'groupId']);
    }

    /**
     * Gets query for [[Member]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(User::class, ['id' => 'memberId']);
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
}
