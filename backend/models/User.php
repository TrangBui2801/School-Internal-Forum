<?php

namespace backend\models;

use common\models\constants\UserRoleConstant;
use common\models\User as ModelsUser;
use Yii;

class User extends ModelsUser
{
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = new \yii\db\Expression('NOW()');
            $this->created_by = Yii::$app->user->identity->id;
            $this->role = UserRoleConstant::USER;
            $this->generateAuthKey();
        } else {
            $this->updated_at = new \yii\db\Expression('NOW()');
            $this->updated_by = Yii::$app->user->identity->id;
        }
        return parent::beforeSave($insert);
    }
}
