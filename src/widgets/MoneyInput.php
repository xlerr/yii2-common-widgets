<?php

namespace xlerr\common\widgets;

use yii\widgets\MaskedInput;

class MoneyInput extends MaskedInput
{
    public $clientOptions = [
        'alias'               => 'numeric',
        'digits'              => 2,
        'groupSeparator'      => ',',
        'radixPoint'          => '.',
        'autoGroup'           => true,
        'autoUnmask'          => true,
        'rightAlign'          => false,
        'enforceDigitsOnBlur' => true,
        'removeMaskOnSubmit'  => true,
        'onUnMask'            => 'function(maskedValue) { return (Number(maskedValue.replace(/,/g, \'\')) * 100).toFixed(0); }',
    ];
}
