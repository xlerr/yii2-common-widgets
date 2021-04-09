<?php

namespace xlerr\common\widgets;

class GridView extends \yii\grid\GridView
{
    public $tableOptions = [
        'class' => 'table table-hover table-striped',
    ];

    public $options = [
        'class' => 'box box-primary',
    ];

    public $pager = [
        'options' => [
            'class' => 'pagination pagination-sm no-margin pull-right',
        ],
    ];

    public $layout = '<div class="box-header with-border">{summary}</div><div class="box-body table-responsive no-padding">{items}</div><div class="box-footer">{pager}</div>';
}
