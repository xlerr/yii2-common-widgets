<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;

class DataTableBootstrapFixedColumnsAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables.net-fixedcolumns-bs';

    public $css = [
        'css/fixedColumns.bootstrap.min.css',
    ];
    public $js  = [
        'js/fixedColumns.bootstrap.min.js',
    ];

    public $depends = [
        DataTableFixedColumnsAsset::class,
        DataTableBootstrapAsset::class,
    ];
}