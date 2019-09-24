<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DataTableAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables/media';

    public $css = [
        'css/jquery.dataTables.css',
    ];

    public $js = [
        'js/jquery.dataTables.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}