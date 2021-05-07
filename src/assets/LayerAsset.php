<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class LayerAsset extends AssetBundle
{
    public $css = [
        'https://cdn.bootcdn.net/ajax/libs/layer/3.3.0/theme/default/layer.min.css',
    ];

    public $js = [
        'https://cdn.bootcdn.net/ajax/libs/layer/3.3.0/layer.min.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
