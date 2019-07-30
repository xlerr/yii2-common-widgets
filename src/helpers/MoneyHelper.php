<?php

namespace xlerr\common\helpers;

class MoneyHelper
{
    const PRECISION  = 2;                   //小数点后2位
    const MODE       = PHP_ROUND_HALF_EVEN; //舍入规则，缺省为四舍六入五凑偶
    const SEP_SYMBOL = ',';                 //千分位符号为半角逗号
    const DEP_SYMBOL = '.';                 //小数点符号

    /**
     * @param $amount
     *
     * @return int
     */
    public static function y2f($amount)
    {
        return intval(round($amount * 100, 0));
    }

    /**
     * @param      $amount
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
    public static function chineseAmount($amount)
    {
        static $cnums = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'],
        $cnyunits = ['圆', '角', '分'],
        $grees = ['拾', '佰', '仟', '万', '拾', '佰', '仟', '亿'];
        list($ns1, $ns2) = explode('.', number_format($amount, 2, '.', ''), 2);
        $ns2 = array_filter([$ns2[1], $ns2[0]]);
        $ret = array_merge($ns2, [implode('', self::cnyMapUnit(str_split($ns1), $grees)), '']);
        $ret = implode('', array_reverse(self::cnyMapUnit($ret, $cnyunits)));

        return str_replace(['零零零', '零零'], ['零', '零'], str_replace(array_keys($cnums), $cnums, $ret));
    }

    private static function cnyMapUnit($list, $units)
    {
        $ul = count($units);
        $xs = [];
        foreach (array_reverse($list) as $x) {
            $l = count($xs);
            if ($x != '0' || !($l % 4)) {
                $n = ($x == '0' ? '' : $x) . (@$units[($l - 1) % $ul]);
            } else {
                $n = is_numeric(@$xs[0][0]) ? $x : '';
            }
            array_unshift($xs, $n);
        }

        return $xs;
    }
}
