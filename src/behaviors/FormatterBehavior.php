<?php

namespace xlerr\common\behaviors;

use xlerr\common\helpers\MoneyHelper;
use yii\base\Behavior;

/**
 * Class FormatterBehavior
 *
 * @package xlerr\common\behaviors
 * @see     \yii\i18n\Formatter
 */
class FormatterBehavior extends Behavior
{
    public function asF2y($value, $format = false)
    {
        return MoneyHelper::f2y($value, $format);
    }
}
