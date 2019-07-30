<?php

namespace xlerr\common\widgets;

use kartik\number\NumberControl;

class MoneyInput extends NumberControl
{
    public $maskedInputOptions = [
        'rightAlign' => false,
    ];
}