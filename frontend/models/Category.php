<?php

namespace frontend\models;

use common\models\Category as ModelsCategory;
use Yii;

class Category extends ModelsCategory
{
    public function beforeSave($insert) {  
        if ($this->isNewRecord) {  
            $this->created_at = new \yii\db\Expression('NOW()');  
            $this->created_by = Yii::$app->user->identity->id;  
            $this->status = 1;  
        } else {  
            $this->updated_at = new \yii\db\Expression('NOW()');  
            $this->updated_by = Yii::$app->user->identity->id;  
        }  
        return parent::beforeSave($insert);  
    }
}
