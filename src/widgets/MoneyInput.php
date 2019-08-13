<?php

namespace xlerr\common\widgets;

use xlerr\common\helpers\MoneyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\MaskedInput;
use yii\widgets\MaskedInputAsset;

class MoneyInput extends MaskedInput
{
    public $clientOptions = [
        'alias'               => 'numeric',
        'digits'              => 2,
        'groupSeparator'      => ',',
        'radixPoint'          => '.',
        'autoGroup'           => true,
        'autoUnmask'          => false,
        'rightAlign'          => false,
        'enforceDigitsOnBlur' => true,
    ];

    protected function renderInputHtml($type)
    {
        $id = ArrayHelper::remove($this->options, 'id');

        $this->options['id'] = $id . '-eldisp';

        $options = [
            'id'       => $id,
            'style'    => 'display:none',
            'disabled' => ArrayHelper::getValue($this->options, 'disabled', false),
            'readonly' => ArrayHelper::getValue($this->options, 'readonly', false),
        ];

        if ($this->hasModel()) {
            $reliable = Html::activeInput($type, $this->model, $this->attribute, $options);
            $value    = $this->model->{$this->attribute};
        } else {
            $reliable = Html::input($type, $this->name, $this->value, $options);
            $value    = $this->value;
        }

        return $reliable . Html::input($type, null, MoneyHelper::f2y($value), $this->options);
    }

    public function registerClientScript()
    {
        $js   = '';
        $view = $this->getView();
        $this->initClientOptions();
        if (!empty($this->mask)) {
            $this->clientOptions['mask'] = $this->mask;
        }
        $this->hashPluginOptions($view);
        if (is_array($this->definitions) && !empty($this->definitions)) {
            $js .= ucfirst(self::PLUGIN_NAME) . '.extendDefinitions(' . Json::htmlEncode($this->definitions) . ');';
        }
        if (is_array($this->aliases) && !empty($this->aliases)) {
            $js .= ucfirst(self::PLUGIN_NAME) . '.extendAliases(' . Json::htmlEncode($this->aliases) . ');';
        }
        $id = $this->options['id'];
        $js .= 'jQuery("#' . $id . '-eldisp").' . self::PLUGIN_NAME . '(' . $this->_hashVar . ');';
        $js .= 'jQuery("#' . $id . '-eldisp").val(jQuery("#' . $id . '").val() / 100).on(\'change blur keypress keydown\', function (e) {
            var event = e.type, key = e.keyCode || e.which, enterKeyPressed = key && parseInt(key) === 13;
            if (event === \'keypress\' && !enterKeyPressed) {
                return;
            }
            if (event !== \'keydown\' || enterKeyPressed) {
                jQuery("#' . $id . '").val($(this).inputmask(\'unmaskedvalue\') * 100).trigger(\'change\');
            }        
        })';
        MaskedInputAsset::register($view);
        $view->registerJs($js);
    }
}