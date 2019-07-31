<?php

namespace xlerr\common\grid;

use xlerr\common\helpers\MoneyHelper;
use yii\grid\DataColumn;

class MoneyDataColumn extends DataColumn
{
    public $headerOptions = [
        'class' => 'text-right',
    ];

    public $contentOptions = [
        'class' => 'text-right',
    ];

    public function init()
    {
        $this->format = function ($amount) {
            return MoneyHelper::f2y($amount, true);
        };
    }
}
