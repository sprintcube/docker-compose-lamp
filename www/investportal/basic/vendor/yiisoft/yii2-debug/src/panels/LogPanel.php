<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\panels;

use Yii;
use yii\debug\models\search\Log;
use yii\debug\Panel;
use yii\log\Logger;

/**
 * Debugger panel that collects and displays logs.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LogPanel extends Panel
{
    /**
     * @var array log messages extracted to array as models, to use with data provider.
     */
    private $_models;


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Logs';
    }

    /**
     * {@inheritdoc}
     */
    public function getSummary()
    {
        return Yii::$app->view->render('panels/log/summary', ['data' => $this->data, 'panel' => $this]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDetail()
    {
        $searchModel = new Log();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this->getModels());

        return Yii::$app->view->render('panels/log/detail', [
            'dataProvider' => $dataProvider,
            'panel' => $this,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $except = [];
        if (isset($this->module->panels['router'])) {
            $except = $this->module->panels['router']->getCategories();
        }

        $messages = $this->getLogMessages(Logger::LEVEL_ERROR | Logger::LEVEL_INFO | Logger::LEVEL_WARNING | Logger::LEVEL_TRACE, [], $except, true);

        return ['messages' => $messages];
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

            $previousId = null;
            $previousTime = null;
            $id = 1;
            foreach ($this->data['messages'] as $message) {
                if (is_null($previousTime)) {
                    $previousTime = $message[3];
                } else {
                    $this->_models[$previousId]['id_of_next'] = $id;
                }
                $this->_models[$id] = [
                    'id' => $id,
                    'message' => $message[0],
                    'level' => $message[1],
                    'category' => $message[2],
                    'time' => $message[3] * 1000, // time in milliseconds
                    'time_of_previous' => $previousTime * 1000, // time in milliseconds
                    'time_since_previous' => $message[3] - $previousTime,
                    'id_of_previous' => $previousId,
                    'id_of_next' => null,
                    'trace' => isset($message[4]) ? $message[4] : [],
                ];
                $previousId = $id;
                $previousTime = $message[3];
                $id++;
            }
        }

        return $this->_models;
    }
}
