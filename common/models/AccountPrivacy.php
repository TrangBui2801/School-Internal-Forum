<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%account_privacy}}".
 *
 * @property int $id
 * @property int|null $userId
 * @property string|null $field_name
 * @property int|null $status
 * @property string|null $created_at
 *
 * @property User $user
 */
class AccountPrivacy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%account_privacy}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'status'], 'integer'],
            [['field_name', 'created_at'], 'string', 'max' => 255],
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
            'field_name' => Yii::t('app', 'Field Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
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

    public function beforeSave($insert)
    {
        $this->created_at = new \yii\db\Expression('NOW()');
        $this->userId = Yii::$app->user->identity->id;
        return parent::beforeSave($insert);
    }
}
