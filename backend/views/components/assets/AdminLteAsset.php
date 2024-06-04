<?php
namespace backend\views\components\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@backend/web/dist';

    public $css = [
        'css/adminlte.min.css',
    ];

    public $js = [
        'js/adminlte.min.js'
    ];

    public $depends = [
        'backend\views\components\assets\BaseAsset',
        'backend\views\components\assets\PluginAsset'
    ];
}