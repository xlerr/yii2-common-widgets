<?php

namespace xlerr\common\grid;

use xlerr\common\widgets\CsvRender;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQueryInterface;
use yii\grid\Column;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class DataColumn extends Column
{
    public $label;

    public $attribute;

    public $value;

    public $format = 'text';

    /**
     * @var CsvRender
     */
    public $grid;

    /**
     * Renders the header cell.
     */
    public function renderHeaderCell()
    {
        return $this->renderHeaderCellContent();
    }

    /**
     * {@inheritdoc}
     */
    protected function renderHeaderCellContent()
    {
        if ($this->header !== null || $this->label === null && $this->attribute === null) {
            $label = parent::renderHeaderCellContent();
        } else {
            $label = $this->getHeaderCellLabel();
            if ($this->grid->isWindows) {
                $label = iconv('utf-8', 'GBK//TRANSLIT//IGNORE', $label);
            }
        }

        return $label;
    }

    /**
     * {@inheritdoc]
     * @since 2.0.8
     */
    protected function getHeaderCellLabel()
    {
        $provider = $this->grid->source;

        if ($this->label === null) {
            if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQueryInterface) {
                /* @var $modelClass Model */
                $modelClass = $provider->query->modelClass;
                $model      = $modelClass::instance();
                $label      = $model->getAttributeLabel($this->attribute);
            } elseif ($provider instanceof ArrayDataProvider && $provider->modelClass !== null) {
                /* @var $modelClass Model */
                $modelClass = $provider->modelClass;
                $model      = $modelClass::instance();
                $label      = $model->getAttributeLabel($this->attribute);
            } else {
                $label = Inflector::camel2words($this->attribute);
            }
        } else {
            $label = $this->label;
        }

        return $label;
    }

    public function renderDataCell($model, $key, $index)
    {
        $value = $this->renderDataCellContent($model, $key, $index);
        if ($this->grid->isWindows) {
            $value = iconv('utf-8', 'GBK//TRANSLIT//IGNORE', $value);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            return $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        }

        return parent::renderDataCellContent($model, $key, $index);
    }

    public function getDataCellValue($model, $key, $index)
    {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                return ArrayHelper::getValue($model, $this->value);
            }

            return call_user_func($this->value, $model, $key, $index, $this);
        } elseif ($this->attribute !== null) {
            return ArrayHelper::getValue($model, $this->attribute);
        }

        return null;
    }
}