<?php

namespace xlerr\common\helpers;

class MoneyHelper
{
    const PRECISION  = 2;                   //小数点后2位
    const MODE       = PHP_ROUND_HALF_EVEN; //舍入规则，缺省为四舍六入五凑偶
    const SEP_SYMBOL = ',';                 //千分位符号为半角逗号
    const DEP_SYMBOL = '.';                 //小数点符号

    /**
     * @param float|int $amount
     *
     * @return int
     */
    public static function y2f($amount)
    {
        return intval(round($amount * 100, 0));
    }

    /**
     * @param int  $amount
     * @param bool $format
     *
     * @return float|string
     */
    public static function f2y($amount, $format = false)
    {
        $amount = round($amount / 100, self::PRECISION, self::MODE);
        if ($format) {
            $amount = self::format($amount);
        }

        return $amount;
    }

    /**
     * @param $amount
     *
     * @return string
     */
    public static function format($amount)
    {
        return number_format($amount, self::PRECISION, self::DEP_SYMBOL, self::SEP_SYMBOL);
    }

    /**
     * @param float|int $amount
     *
     * @return string
     */
    public static function amountHuman($amount): string
    {
        list($integer, $float) = explode('.', $amount, 2) + [1 => null];
        $integerLen = strlen($integer) - 1;
        $numHuman   = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];
        $unit       = ['', '拾', '佰', '仟', '万', '拾', '佰', '仟', '亿', '拾', '佰', '仟', '万', '拾', '佰', '仟'];
        $data       = '';
        for ($i = $integerLen; $i >= 0; $i--) {
            $data = $numHuman[$integer[$i]] . $unit[$integerLen - $i] . $data;
        }

        $data = preg_replace_callback('/(零(万|亿|$))+/', function ($match) {
                return substr($match[0], 3, 3);
            }, strtr(preg_replace('/(零(拾|佰|仟))+/', '零', $data), ['零零' => '零'])) . '圆';

        if (empty($float)) {
            $data .= '整';
        } else {
            $dime = $float[0] ?? 0;
            $cent = $float[1] ?? 0;
            if ($dime) {
                $data .= $numHuman[$dime] . '角';
            }
            if ($cent) {
                $data .= ($dime ? '' : '零') . $numHuman[$cent] . '分';
            }
        }

        return $data;
    }

    /**
     * @param float|int $amount
     *
     * @return string
     * @deprecated
     * @uses \xlerr\common\helpers\MoneyHelper::amountHuman()
     */
    public static function chineseAmount($amount): string
    {
        return self::amountHuman($amount);
    }
}
