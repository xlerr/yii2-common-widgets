<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DataTableAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables.net';

    public $js = [
        'js/jquery.dataTables.min.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}