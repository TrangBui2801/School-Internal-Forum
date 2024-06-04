<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property int $id
 * @property int|null $parentId
 * @property string|null $file_extension
 * @property string|null $file_type
 * @property string|null $url
 * @property string|null $original_name
 * @property int|null $status
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 *
 * @property Post $parent
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parentId', 'status', 'created_by', 'updated_by'], 'integer'],
            [['url'], 'string'],
            [['file_type', 'file_extension', 'original_name', 'created_at', 'updated_at'], 'string', 'max' => 255],
            [['parentId'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['parentId' => 'id']],
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
            'file_type' => Yii::t('app', 'File Type'),
            'file_extension' => Yii::t('app', 'File Extension'),
            'url' => Yii::t('app', 'Url'),
            'original_name' => Yii::t('app', 'Original Name'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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
