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
    ];

    public function init()
    {
        parent::init();

        $this->clientOptions += [
            'onUnMask' => 'function(maskedValue) { return (Number(maskedValue.replace(/,/g, \'\')) * 100).toFixed(0); }',
        ];
    }

    public function registerClientScript()
    {
        parent::registerClientScript();

        $id = $this->options['id'];

        $value = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        $value = round($value / 100, 2);

        $js = <<<EOF
jQuery("#{$id}").val({$value});
EOF;

        $this->getView()->registerJs($js);
    }
}
