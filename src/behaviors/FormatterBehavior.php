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
    /**
     * @param int  $amount
     * @param bool $format
     *
     * @return float|string
     */
    public function asF2y($amount, $format = false)
    {
        return MoneyHelper::f2y($amount, $format);
    }

    /**
     * @param float|int $amount
     *
     * @return int
     */
    public function asY2f($amount)
    {
        return MoneyHelper::y2f($amount);
    }

    /**
     * @param float|int $amount
     *
     * @return string
     */
    public function asChineseAmount($amount)
    {
        return MoneyHelper::chineseAmount($amount);
    }

    /**
     * @param mixed $value
     * @param array $range
     * @param mixed $default
     *
     * @return mixed
     */
    public function asIn($value, array $range = [], $default = null)
    {
        if (null === $default) {
            $default = $value;
        }

        return isset($range[$value]) ? $range[$value] : $default;
    }
}
