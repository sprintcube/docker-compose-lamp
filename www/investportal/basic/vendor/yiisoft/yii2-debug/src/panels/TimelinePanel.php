<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\panels;

use Yii;
use yii\base\InvalidConfigException;
use yii\debug\models\timeline\Search;
use yii\debug\models\timeline\Svg;
use yii\debug\Panel;

/**
 * Debugger panel that collects and displays timeline data.
 *
 * @property array $colors
 * @property-read float $duration This property is read-only.
 * @property-read float $start This property is read-only.
 * @property array $svgOptions
 *
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 * @since 2.0.7
 */
class TimelinePanel extends Panel
{
    /**
     * @var array Color indicators item profile.
     *
     * - keys: percentages of time request
     * - values: hex color
     */
    private $_colors = [
        20 => '#1e6823',
        10 => '#44a340',
        1 => '#8cc665'
    ];
    /**
     * @var array log messages extracted to array as models, to use with data provider.
     */
    private $_models;
    /**
     * @var float Start request, timestamp (obtained by microtime(true))
     */
    private $_start;
    /**
     * @var float End request, timestamp (obtained by microtime(true))
     */
    private $_end;
    /**
     * @var float Request duration, milliseconds
     */
    private $_duration;
    /**
     * @var Svg|null
     */
    private $_svg;
    /**
     * @var array
     */
    private $_svgOptions = [
        'class' => 'yii\debug\models\timeline\Svg'
    ];
    /**
     * @var int Used memory in request
     */
    private $_memory;


    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!isset($this->module->panels['profiling'])) {
            throw new InvalidConfigException('Unable to determine the profiling panel');
        }
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Timeline';
    }

    /**
     * {@inheritdoc}
     */
    public function getDetail()
    {
        $searchModel = new Search();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this);

        return Yii::$app->view->render('panels/timeline/detail', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function load($data)
    {
        if (!isset($data['start']) || empty($data['start'])) {
            throw new \RuntimeException('Unable to determine request start time');
        }
        $this->_start = $data['start'] * 1000;

        if (!isset($data['end']) || empty($data['end'])) {
            throw new \RuntimeException('Unable to determine request end time');
        }
        $this->_end = $data['end'] * 1000;

        if (isset($this->module->panels['profiling']->data['time'])) {
            $this->_duration = $this->module->panels['profiling']->data['time'] * 1000;
        } else {
            $this->_duration = $this->_end - $this->_start;
        }

        if ($this->_duration <= 0) {
            throw new \RuntimeException('Duration cannot be zero');
        }

        if (!isset($data['memory']) || empty($data['memory'])) {
            throw new \RuntimeException('Unable to determine used memory in request');
        }
        $this->_memory = $data['memory'];
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        return [
            'start' => YII_BEGIN_TIME,
            'end' => microtime(true),
            'memory' => memory_get_peak_usage(),
        ];
    }

    /**
     * Sets color indicators.
     * key: percentages of time request, value: hex color
     * @param array $colors
     */
    public function setColors($colors)
    {
        krsort($colors);
        $this->_colors = $colors;
    }

    /**
     * Color indicators item profile,
     * key: percentages of time request, value: hex color
     * @return array
     */
    public function getColors()
    {
        return $this->_colors;
    }

    /**
     * @param array $options
     */
    public function setSvgOptions($options)
    {
        if ($this->_svg !== null) {
            $this->_svg = null;
        }
        $this->_svgOptions = array_merge($this->_svgOptions, $options);
    }

    /**
     * @return array
     */
    public function getSvgOptions()
    {
        return $this->_svgOptions;
    }

    /**
     * Start request, timestamp (obtained by microtime(true))
     * @return float
     */
    public function getStart()
    {
        return $this->_start;
    }

    /**
     * Request duration, milliseconds
     * @return float
     */
    public function getDuration()
    {
        return $this->_duration;
    }

    /**
     * Memory peak in request, bytes. (obtained by memory_get_peak_usage())
     * @return int
     * @since 2.0.8
     */
    public function getMemory()
    {
        return $this->_memory;
    }

    /**
     * @return Svg
     * @since 2.0.8
     * @throws InvalidConfigException
     */
    public function getSvg()
    {
        if ($this->_svg === null) {
            $this->_svg = Yii::createObject($this->_svgOptions, [$this]);
        }
        return $this->_svg;
    }

    /**
     * Returns an array of models that represents logs of the current request.
     * Can be used with data providers, such as \yii\data\ArrayDataProvider.
     *
     * @param bool $refresh if need to build models from log messages and refresh them.
     * @return array models
     */
    protected function getModels($refresh = false)
    {
        if ($this->_models === null || $refresh) {
            $this->_models = [];
            if (isset($this->module->panels['profiling']->data['messages'])) {
                $this->_models = Yii::getLogger()->calculateTimings($this->module->panels['profiling']->data['messages']);
            }
        }
        return $this->_models;
    }
}
