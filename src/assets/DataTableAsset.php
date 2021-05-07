<?php

namespace xlerr\common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DataTableAsset extends AssetBundle
{
    public $css = [
        'https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap.min.css',
        'https://cdn.datatables.net/fixedcolumns/3.3.2/css/fixedColumns.bootstrap.min.css',
        'https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.bootstrap.min.css',
    ];

    public $js = [
        'https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap.min.js',
        'https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js',
        'https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js',
    ];
}
