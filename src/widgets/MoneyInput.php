<?php

namespace xlerr\common\widgets;

use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;
use yii\widgets\MaskedInputAsset;

class MoneyInput extends MaskedInput
{
    public $clientOptions = [
        'alias' => 'money',
    ];

    public function init()
    {
        parent::init();

        $this->aliases['money'] = [
            'alias'               => 'numeric',
            'digits'              => 2,
            'groupSeparator'      => ',',
            'radixPoint'          => '.',
            'autoGroup'           => true,
            'autoUnmask'          => true,
            'rightAlign'          => false,
            'enforceDigitsOnBlur' => true,
            'removeMaskOnSubmit'  => true,
            'onBeforeMask'        => new JsExpression('function (v) { v = Number(v); return (isNaN(v) ? 0 : v / 100).toFixed(2) }'),
            'onUnMask'            => new JsExpression('function(v) { return (Number(v.replace(/,/g, \'\')) * 100).toFixed(0) }'),
        ];
    }

    public function registerClientScript()
    {
        $this->initClientOptions();
        if (!empty($this->mask)) {
            $this->clientOptions['mask'] = $this->mask;
        }

        $view = $this->getView();
        MaskedInputAsset::register($view);

        $this->hashPluginOptions($view);

        if (is_array($this->definitions) && !empty($this->definitions)) {
            $view->registerJs(vsprintf('%s.extendDefinitions(%s);', [
                ucfirst(self::PLUGIN_NAME),
                Json::htmlEncode($this->definitions),
            ]));
        }

        if (is_array($this->aliases) && !empty($this->aliases)) {
            $view->registerJs(vsprintf('%s.extendAliases(%s);', [
                ucfirst(self::PLUGIN_NAME),
                Json::htmlEncode($this->aliases),
            ]));
        }

        $view->registerJs(vsprintf('jQuery("#%s").%s(%s);', [
            $this->options['id'],
            self::PLUGIN_NAME,
            $this->_hashVar,
        ]));
    }
}
