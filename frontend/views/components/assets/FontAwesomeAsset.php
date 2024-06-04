<?php
namespace frontend\views\components\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@frontend/web/plugins/fontawesome-free';

    public $css = [
        'css/all.min.css'
    ];
}