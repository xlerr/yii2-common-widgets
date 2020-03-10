<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class LayerAsset extends AssetBundle
{
    public $sourcePath = '@bower/layer/dist';

    public $css = [
        'theme/default/layer.css',
    ];

    public $js = [
        'layer.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
