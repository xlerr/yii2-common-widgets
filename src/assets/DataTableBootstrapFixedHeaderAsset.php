<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;

class DataTableBootstrapFixedHeaderAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables.net-fixedheader-bs';

    public $js = [
        'js/fixedHeader.bootstrap.min.js',
    ];

    public $css = [
        'css/fixedHeader.bootstrap.min.css',
    ];

    public $depends = [
        DataTableFixedHeaderAsset::class,
        DataTableBootstrapAsset::class,
    ];
}