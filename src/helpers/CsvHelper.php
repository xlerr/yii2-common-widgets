<?php

namespace xlerr\common\helpers;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\grid\DataColumn;
use yii\i18n\Formatter;

class CsvHelper extends BaseObject
{
    public $source;

    public $columns = [];

    public $key;

    public $formatter;

    protected $stream;

    protected $isWindows;

    /**
     * 渲染表格
     *
     * @param array|yii\db\Query|\yii\db\QueryInterface|ActiveDataProvider $source 数据源
     * @param array                                                        $columns
     * @param callable|null                                                $getter
     *
     * @return resource
     * @throws \yii\base\InvalidConfigException
     * @example CsvHelper::build($query, [
     *           [
     *               'label' => 'column 1',
     *               'value' => 'id',
     *           ],
     *           [
     *               'label'     => '#1',
     *               'attribute' => 'title',
     *           ],
     *           [
     *               'label' => '#2',
     *               'value' => function ($row) {
     *                   return $row['content'];
     *               },
     *           ],
     *           [
     *               'label'     => '#4',
     *               'attribute' => 'amount',
     *               'format'    => ['f2y', true],
     *           ],
     *           'created_at:date:DATE',
     *       ]);
     */
    public static function build($source, array $columns = [], callable $getter = null)
    {
        if (null === $getter) {
            $getter = self::getter($source);
        }

        $self = Yii::createObject([
            'class'   => self::class,
            'source'  => $source,
            'columns' => $columns,
        ]);

        return $self->render($getter);
    }

    /**
     * @param $source
     *
     * @return \Closure
     */
    public static function getter(&$source)
    {
        if (is_array($source)) {
            return function ($source) {
                foreach ($source as $row) {
                    yield $row;
                }
            };
        }

        if ($source instanceof ActiveDataProvider) {
            /** @var ActiveDataProvider $source */
            $sort   = $source->getSort();
            $source = $source->query;
            if ($sort !== false) {
                $source->addOrderBy($sort->getOrders());
            }
        }

        if ($source instanceof Query) {
            return function (Query $source) {
                foreach ($source->each(200) as $row) {
                    yield $row;
                }
            };
        }

        throw new InvalidArgumentException('未知源');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->stream = fopen('php://temp', 'r+') or die("Can't open php://temp");
        $this->isWindows = preg_match('/windows/i', Yii::$app->getRequest()->getUserAgent()) === 1;

        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }

        $this->initColumns();
    }

    /**
     * @param callable $method
     *
     * @return resource
     */
    public function render(callable $method)
    {
        $cells = [];
        /** @var DataColumn $column */
        foreach ($this->columns as $column) {
            if ($this->isWindows) {
                $cells[] = iconv('utf-8', 'GBK//TRANSLIT//IGNORE', $column->label);
            } else {
                $cells[] = $column->label;
            }
        }
        fputcsv($this->stream, $cells);

        foreach ($method($this->source) as $index => $row) {
            $cells = [];
            /* @var DataColumn $column */
            foreach ($this->columns as $column) {
                $val = $column->renderDataCell($row, $this->key, $index);
                if ($this->isWindows) {
                    $val = iconv('utf-8', 'GBK//TRANSLIT//IGNORE', $val);
                }
                $cells[] = $val;
            }
            fputcsv($this->stream, $cells);
        }

        return $this->stream;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function initColumns()
    {
        $columns =& $this->columns;
        foreach ($columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = $this->createDataColumnObject(array_merge([
                    'grid' => $this,
                ], $column));
            }
            if (!$column->visible) {
                unset($columns[$i]);
                continue;
            }
            $columns[$i] = $column;
        }
    }

    /**
     * @param $text
     *
     * @return \yii\grid\DataColumn
     * @throws \yii\base\InvalidConfigException
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return $this->createDataColumnObject([
            'grid'      => $this,
            'attribute' => $matches[1],
            'format'    => isset($matches[3]) ? $matches[3] : 'text',
            'label'     => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    /**
     * @param array $config
     *
     * @return \yii\grid\DataColumn
     */
    protected function createDataColumnObject($config = [])
    {
        $class = $config['class'] ?? null;
        unset($config['class']);

        $column = new class($config) extends DataColumn
        {
            public function renderDataCell($model, $key, $index)
            {
                return $this->renderDataCellContent($model, $key, $index);
            }
        };

        if ($class && class_exists($class)) {
            $distColumn = new $class($config);

            $column->format = $distColumn->format;
        }

        return $column;
    }
}
