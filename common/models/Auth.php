<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%auth}}".
 *
 * @property int $id
 * @property int|null $userId
 * @property string|null $source
 * @property string|null $sourceId
 *
 * @property User $user
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId'], 'integer'],
            [['source', 'sourceId'], 'string', 'max' => 255],
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
            'source' => Yii::t('app', 'Source'),
            'sourceId' => Yii::t('app', 'Source ID'),
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
}
