<?php
namespace frontend\views\components\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@frontend/web/dist';

    public $css = [
        'css/adminlte.min.css'
    ];

    public $js = [
        'js/adminlte.min.js'
    ];

    public $depends = [
        'frontend\views\components\assets\BaseAsset',
        'frontend\views\components\assets\PluginAsset'
    ];
}