<?php

namespace xlerr\common\grid;

use xlerr\common\helpers\MoneyHelper;
use yii\grid\DataColumn;
use yii\helpers\Html;

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
            return Html::tag('span', MoneyHelper::f2y($amount, true), [
                'title' => MoneyHelper::chineseAmount(MoneyHelper::f2y($amount)),
            ]);
        };
    }
}
