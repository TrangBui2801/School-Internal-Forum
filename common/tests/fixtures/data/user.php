<?php

use common\models\constants\StatusConstant;
use common\models\constants\UserRoleConstant;

return [
    [
        'id' => '1',
        '_username' => 'admin',
        '_password' => '$2y$13$qfJBI4fPBH5fbJNO9bT9Iu8tBvU/EWMSsNFNNP7nLKzTCdn4OBA8G',
        'full_name' => 'Administrator',
        'email' => 'ngocatp.52@gmail.com',
        'address' => '21C1 CT2A Chung cư Xuân Phương - Quốc Hội, Nam Từ Liêm, Hà Nội',
        'phone_number' => '0941900193',
        'birthday' => '20/10/2010',
        'avatar' => 'D:\FGW\Final\Crush\Forum/backend/web/uploads/users_avatar/user_no_avatar/User-avatar.png',
        'gender' => 'Male',
        'introduction' => 'Admin of system',
        'short_introduction' => 'Admin of system',
        'facebook_link' => 'https://www.facebook.com/thang.he.khoc.1193/',
        'skype_link' => null,
        'github_link' => 'https://github.com/dominhngoc123',
        'youtube_link' => 'https://www.youtube.com/watch?v=EYJimpsoE3g',
        'departmentId' => '1',
        'role' => UserRoleConstant::ADMIN,
        'status' => StatusConstant::ACTIVE,
        'auth_key' => 'JkcsaJ4Zn0TqlSxL5ITjoEN6_8DZyX0i',
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
];