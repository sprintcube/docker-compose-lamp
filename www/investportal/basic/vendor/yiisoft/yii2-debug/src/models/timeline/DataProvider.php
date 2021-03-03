<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models\timeline;

use yii\data\ArrayDataProvider;
use yii\debug\panels\TimelinePanel;

/**
 * DataProvider implements a data provider based on a data array.
 *
 * @property-read array $rulers This property is read-only.
 *
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 * @since 2.0.8
 */
class DataProvider extends ArrayDataProvider
{
    /**
     * @var TimelinePanel
     */
    protected $panel;


    /**
     * DataProvider constructor.
     * @param TimelinePanel $panel
     * @param array $config
     */
    public function __construct(TimelinePanel $panel, $config = [])
    {
        $this->panel = $panel;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        if (($models = $this->allModels) === null) {
            return [];
        }
        $child = [];
        foreach ($models as $key => &$model) {
            $model['timestamp'] *= 1000;
            $model['duration'] *= 1000;
            $model['child'] = 0;
            $model['css']['width'] = $this->getWidth($model);
            $model['css']['left'] = $this->getLeft($model);
            $model['css']['color'] = $this->getColor($model);
            foreach ($child as $id => $timestamp) {
                if ($timestamp > $model['timestamp']) {
                    ++$models[$id]['child'];
                } else {
                    unset($child[$id]);
                }
            }
            $child[$key] = $model['timestamp'] + $model['duration'];
        }
        return $models;
    }

    /**
     * Getting HEX color based on model duration
     * @param array $model
     * @return string
     */
    public function getColor($model)
    {
        $width = isset($model['css']['width']) ? $model['css']['width'] : $this->getWidth($model);
        foreach ($this->panel->colors as $percent => $color) {
            if ($width >= $percent) {
                return $color;
            }
        }
        return '#d6e685';
    }

    /**
     * Returns the offset left item, percentage of the total width
     * @param array $model
     * @return float
     */
    public function getLeft($model)
    {
        return $this->getTime($model) / ($this->panel->duration / 100);
    }

    /**
     * Returns item duration, milliseconds
     * @param array $model
     * @return float
     */
    public function getTime($model)
    {
        return $model['timestamp'] - $this->panel->start;
    }

    /**
     * Returns item width percent of the total width
     * @param array $model
     * @return float
     */
    public function getWidth($model)
    {
        return $model['duration'] / ($this->panel->duration / 100);
    }

    /**
     * Returns item, css class
     * @param array $model
     * @return string
     */
    public function getCssClass($model)
    {
        $class = 'time';
        $class .= (($model['css']['left'] > 15) && ($model['css']['left'] + $model['css']['width'] > 50)) ? ' right' : ' left';
        return $class;
    }

    /**
     * ruler items, key milliseconds, value offset left
     * @param int $line number of columns
     * @return array
     */
    public function getRulers($line = 10)
    {
        if ($line == 0) {
            return [];
        }
        $data = [0];
        $percent = ($this->panel->duration / 100);
        $row = $this->panel->duration / $line;
        $precision = $row > 100 ? -2 : -1;
        for ($i = 1; $i < $line; $i++) {
            $ms = round($i * $row, $precision);
            $data[$ms] = $ms / $percent;
        }
        return $data;
    }

    /**
     * ```php
     * [
     *   0 => string, memory usage (MB)
     *   1 => float, Y position (percent)
     * ]
     * @param array $model
     * @return array|null
     */
    public function getMemory($model)
    {
        if (empty($model['memory'])) {
            return null;
        }

        return [
            sprintf('%.2f MB', $model['memory'] / 1048576),
            $model['memory'] / ($this->panel->memory / 100)
        ];
    }
}
