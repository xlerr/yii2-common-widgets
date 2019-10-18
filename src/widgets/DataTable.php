<?php

namespace xlerr\common\widgets;

use xlerr\common\assets\DataTableBootstrapAsset;
use xlerr\common\assets\DataTableBootstrapFixedColumnsAsset;
use xlerr\common\assets\DataTableBootstrapFixedHeaderAsset;
use xlerr\common\assets\LayerAsset;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class DataTable
 *
 * @package xlerr\common\widgets
 */
class DataTable extends \yii\grid\GridView
{
    public $tableOptions = [
        'class' => 'table table-hover table-striped',
        'style' => 'width: 100%',
    ];

    public $options = [
        'class' => 'box box-primary',
    ];

    public $pager = [
        'options' => [
            'class' => 'pagination pagination-sm no-margin pull-right',
        ],
    ];

    /**
     * @var array
     * @see https://datatables.net/reference/option/
     */
    public $dataTableOptions = [];

    public $dataTableEvents = [];

    public $layout = '<div class="box-header with-border">{summary}</div><div class="box-body table-responsive no-padding">{items}</div><div class="box-footer">{pager}</div>';

    public function init()
    {
        $this->dataTableOptions = array_merge([
            //            'fixedHeader' => [
            //                'header' => true,
            //                'footer' => true,
            //            ],
            'paginate'  => false,
            'ordering'  => false,
            'searching' => false,
            'info'      => false,
            'scrollX'   => true,
            'scrollY'   => false,
        ], $this->dataTableOptions);

        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        parent::run();

        $view = $this->getView();

        LayerAsset::register($view);
        DataTableBootstrapAsset::register($view);
        DataTableBootstrapFixedHeaderAsset::register($view);
        DataTableBootstrapFixedColumnsAsset::register($view);

        $options = Json::htmlEncode($this->dataTableOptions);

        $id = $this->options['id'];

        $events = '';
        foreach ($this->dataTableEvents as $event => $func) {
            $events .= vsprintf("\n.on('%s', %s)", [
                $event,
                ($func instanceof JsExpression ? $func : new JsExpression($func)),
            ]);
        }

        $view->registerJs("try{ jQuery('#$id table')$events\n.dataTable($options); } catch(e) { console.warn(e); }");
        $this->registerHoverStyle($id);
    }

    public function registerHoverStyle($id)
    {
        $hoverScript = <<<JAVASCRIPT
const dataTableBody{$id} = $('#{$id} table.dataTable > tbody');
dataTableBody{$id}.each(function () {
    $(this).children('tr').dblclick(function () {
        let trText = [];
        $(this).find('td').each(function () {
            trText.push($(this).text());
        });
        const content = trText.join('\t').replace(/^\s+|\s+$/, ''),
            input = $('<input>', {value: content});
        input.appendTo('body').select();
        document.execCommand('copy');
        input.remove();
        layer.msg('已复制一行数据!', {time: 1000});
    }).click(function () {
        return;
        const self = $(this),
            index = self.index();
        if (self.hasClass('bg-gray')) {
            dataTableBody{$id}.each(function () {
                $(this).children('tr:eq(' + index + ')').removeClass('bg-gray');
            });
        } else {
            dataTableBody{$id}.each(function () {
                $(this).children('tr:eq(' + index + ')').addClass('bg-gray');
            });
        }
    }).hover(function () {
        const index = $(this).index();
        dataTableBody{$id}.each(function () {
            $(this).children('tr:eq(' + index + ')').css({
                'background-color': '#f5f5f5',
            });
        });
    }, function () {
        const index = $(this).index();
        dataTableBody{$id}.each(function () {
            const target = $(this).children('tr:eq(' + index + ')');
            target.css({
                'background-color': target.hasClass('odd') ? '#f9f9f9' : 'white',
            });
        });
    });
});
JAVASCRIPT;
        $this->getView()->registerJs($hoverScript);
    }
}
