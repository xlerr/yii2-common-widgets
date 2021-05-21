<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;

/**
 * Class HighlightJsAsset
 *
 * @package xlerr\common\assets
 */
class HighlightJsAsset extends AssetBundle
{
    public $css = [
        '//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/github.min.css',
    ];

    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js',
    ];
}
