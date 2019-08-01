<?php

namespace xlerr\common\grid;

use yii\grid\DataColumn;

class MoneyDataColumn extends DataColumn
{
    public $headerOptions = [
        'class' => 'text-right',
    ];

    public $contentOptions = [
        'class' => 'text-right',
    ];

    public $format = ['f2y', true];
}
