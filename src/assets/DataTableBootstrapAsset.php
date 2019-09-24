<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;

class DataTableBootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables.net-bs';

    public $css = [
        'css/dataTables.bootstrap.min.css',
    ];

    public $js = [
        'js/dataTables.bootstrap.min.js',
    ];

    public $depends = [
        DataTableAsset::class,
    ];
}