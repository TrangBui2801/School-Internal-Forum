<?php

namespace common\models;

use common\models\constants\StatusConstant;
use Yii;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property int $id
 * @property string $label
 * @property int|null $parentId
 * @property int|null $level
 * @property int|null $status
 * @property string|null $url
 * @property string|null $icon
 * @property string|null $icon_style
 * @property int|null $level
 * @property string $created_at
 * @property int $created_by
 * @property string|null $updated_at
 * @property int|null $updated_by
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['parentId', 'status', 'level', 'created_by', 'updated_by', 'level'], 'integer'],
            [['label'], 'string', 'max' => 50],
            [['url', 'icon'], 'string', 'max' => 50],
            [['icon_style'], 'string', 'max' => 20],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
            [['label'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Label'),
            'parentId' => Yii::t('app', 'Parent ID'),
            'level' => Yii::t('app', 'Level'),
            'status' => Yii::t('app', 'Status'),
            'url' => Yii::t('app', 'Url'),
            'icon' => Yii::t('app', 'Icon'),
            'icon_style' => Yii::t('app', 'Icon Style'),
            'level' => Yii::t('app', 'Level'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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

    public function getMenus()
    {
        return $this->hasMany(Menu::class, ['parentId' => 'id']);
    }

    public function getSubmenus()
    {
        return $this->hasMany(Menu::class, ['parentId' => 'id'])->inverseOf('menus');
    }
}
