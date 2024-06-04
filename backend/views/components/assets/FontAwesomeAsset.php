<?php
namespace backend\views\components\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@backend/web/plugins/fontawesome-free';

    public $css = [
        'css/all.min.css'
    ];
}