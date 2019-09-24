<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;

class DataTableFixedColumnsAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables.net-fixedcolumns';

    public $js = [
        'js/dataTables.fixedColumns.min.js',
    ];

    public $depends = [
        DataTableAsset::class,
    ];
}