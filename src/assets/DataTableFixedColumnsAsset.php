<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;

class DataTableFixedColumnsAsset extends AssetBundle
{
    public $sourcePath = '@bower/datatables-fixedcolumns';

    public $css = [
        //        'css/',
    ];

    public $js = [
        'js/dataTables.fixedColumns.js',
    ];

    public $depends = [
        DataTableAsset::class,
    ];
}