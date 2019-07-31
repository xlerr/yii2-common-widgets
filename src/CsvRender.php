<?php

namespace xlerr\common;

use xlerr\common\grid\DataColumn;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\DataReader;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\i18n\Formatter;

/**
 * Class CsvRender
 *
 * @example
 * $stream = (new CsvRender([
 *         'source' = new Query(),
 *         'columns' => [],
 * ])->run();
 * @package xlerr\common\widgets
 */
class CsvRender extends BaseObject
{
    /**
     * @var array the HTML attributes for the container tag of the list view.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var array
     * @see \yii\grid\GridView::$columns
     */
    public $columns = [];

    /**
     * @var \yii\data\DataProviderInterface|yii\db\Query|array the data source for the view. This property is required.
     */
    public $source;

    /**
     * @var string|false
     */
    public $emptyText;

    /**
     * @var resource
     */
    public $stream;

    public $formatter;

    /**
     * @var boolean
     */
    public $isWindows;

    public $dataColumnClass;

    /**
     * Initializes the view.
     */
    public function init()
    {
        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
        if ($this->source === null) {
            throw new InvalidConfigException('The "source" property must be set.');
        }
        if (!is_resource($this->stream)) {
            $this->stream = fopen('php://temp', 'r+') or die("Can't open php://temp");
        }
        if ($this->emptyText === null) {
            $this->emptyText = Yii::t('yii', 'No results found.');
        }
        if ($this->isWindows === null) {
            $isWindows = preg_match('/windows/i', Yii::$app->getRequest()->getUserAgent()) === 1;
        }

        $this->initColumns();
    }

    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => $this->dataColumnClass ?? DataColumn::class,
                    'grid'  => $this,
                ], $column));
            }
            if (!$column->visible) {
                unset($this->columns[$i]);
                continue;
            }
            $this->columns[$i] = $column;
        }
    }

    /**
     * Creates a [[DataColumn]] object based on a string in the format of "attribute:format:label".
     *
     * @param string $text the column specification string
     *
     * @return DataColumn the column instance
     * @throws InvalidConfigException if the column specification is invalid
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return Yii::createObject([
            'class'     => $this->dataColumnClass ?? DataColumn::class,
            'grid'      => $this,
            'attribute' => $matches[1],
            'format'    => isset($matches[3]) ? $matches[3] : 'text',
            'label'     => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    public function renderHeader()
    {
        $cells = [];
        /** @var DataColumn $column */
        foreach ($this->columns as $column) {
            $cells[] = $column->renderHeaderCell();
        }

        fputcsv($this->stream, $cells);
    }

    public function renderBody()
    {
        $source = $this->source;
        if ($source instanceof ArrayDataProvider) {
            return $this->renderArray($source->allModels);
        } elseif (is_array($source)) {
            return $this->renderArray($source);
        } elseif ($source instanceof Query) {
            return $this->renderQuery($source);
        } elseif ($source instanceof ActiveDataProvider) {
            return $this->renderQuery($source->query, $source->getKeys());
        } elseif ($source instanceof SqlDataProvider) {
            $command = $source->db->createCommand($source->sql, $source->params);

            return $this->renderDataReader($command->query());
        } else {
            throw new InvalidArgumentException('未知源');
        }
    }

    /**
     * @param array $source
     *
     * @return int
     */
    public function renderArray($source)
    {
        return $this->renderItems($source, function (array $source) {
            foreach ($source as $row) {
                yield $row;
            }
        });
    }

    /**
     * @param Query $source
     *
     * @return int
     */
    public function renderQuery($source)
    {
        return $this->renderItems($source, function (Query $source) {
            foreach ($source->each(200) as $row) {
                yield $row;
            }
        });
    }

    /**
     * @param \yii\db\DataReader $source
     *
     * @return int
     */
    public function renderDataReader($source)
    {
        return $this->renderItems($source, function (DataReader $source) {
            while ($row = $source->read()) {
                yield $row;
            }
        });
    }

    /**
     * @param array|Query $source
     * @param callable    $method
     *
     * @return int render rows
     */
    public function renderItems($source, callable $method)
    {
        foreach ($method($source) as $index => $row) {
            $row = $this->renderRow($row, $index, $index);
            fputcsv($this->stream, $row);
        }

        return $index;
    }

    /**
     * @param array|\yii\base\Model $model
     * @param                       $key
     * @param                       $index
     *
     * @return array
     */
    public function renderRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column DataColumn */
        foreach ($this->columns as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index);
        }

        return $cells;
    }

    /**
     * Runs the widget.
     *
     * @return resource
     */
    public function run()
    {
        $this->renderHeader();

        $rowCount = $this->renderBody();
        if ($rowCount < 0) {
            $this->renderEmpty();
        }

        return $this->stream;
    }

    /**
     * Renders the HTML content indicating that the list view has no data.
     *
     * @return string the rendering result
     * @see emptyText
     */
    public function renderEmpty()
    {
        if ($this->emptyText === false) {
            return '';
        }
        $options = $this->emptyTextOptions;
        $tag     = ArrayHelper::remove($options, 'tag', 'div');

        return Html::tag($tag, $this->emptyText, $options);
    }
}
