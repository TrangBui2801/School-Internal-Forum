<?php

namespace common\helpers;

class ImageUrlHelper {
    public static function getImageUrl ($url){
        return stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://' . 'admin.ep.com' . substr($url, strripos($url, '/uploads'), strlen($url));
    }
}
