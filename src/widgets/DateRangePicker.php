<?php

namespace xlerr\common\widgets;

class DateRangePicker extends \kartik\daterange\DateRangePicker
{
    public $presetDropdown = true;
    public $convertFormat = true;
    public $pluginOptions = [
        'locale' => [
            'format' => 'Y-m-d',
        ],
        'opens'  => 'right',
        'ranges' => [
            '今日'    => ["moment().startOf('day')", "moment().endOf('day')"],
            '昨日'    => [
                "moment().startOf('day').subtract(1,'days')",
                "moment().endOf('day').subtract(1,'days')",
            ],
            '最近7天'  => ["moment().startOf('day').subtract(6, 'days')", "moment().endOf('day')"],
            '最近30天' => ["moment().startOf('day').subtract(29, 'days')", "moment().endOf('day')"],
            '最近90天' => ["moment().startOf('day').subtract(89, 'days')", "moment().endOf('day')"],
            '本周'    => ["moment().startOf('week')", "moment().endOf('week')"],
            '上周'    => [
                "moment().subtract(1, 'week').startOf('week')",
                "moment().subtract(1, 'week').endOf('week')",
            ],
            '本月'    => ["moment().startOf('month')", "moment().endOf('month')"],
            '上月'    => [
                "moment().subtract(1, 'month').startOf('month')",
                "moment().subtract(1, 'month').endOf('month')",
            ],
            '今年'    => ["moment().startOf('year')", "moment().endOf('year')"],
            '去年'    => [
                "moment().subtract(1, 'year').startOf('year')",
                "moment().subtract(1, 'year').endOf('year')",
            ],
        ],
    ];
}
