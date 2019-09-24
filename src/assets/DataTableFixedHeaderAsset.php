<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;

class DataTableFixedHeaderAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables.net-fixedheader';

    public $js = [
        'js/dataTables.fixedHeader.min.js',
    ];

    public $depends = [
        DataTableAsset::class,
    ];
}