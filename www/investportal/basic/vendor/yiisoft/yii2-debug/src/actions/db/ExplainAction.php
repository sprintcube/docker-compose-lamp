<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\actions\db;

use yii\base\Action;
use yii\debug\panels\DbPanel;
use yii\web\HttpException;

/**
 * ExplainAction provides EXPLAIN information for SQL queries
 *
 * @author Laszlo <github@lvlconsultancy.nl>
 * @since 2.0.6
 */
class ExplainAction extends Action
{
    /**
     * @var DbPanel
     */
    public $panel;


    /**
     * Runs the action.
     *
     * @param string $seq
     * @param string $tag
     * @return string
     * @throws HttpException
     * @throws \yii\db\Exception
     * @throws \yii\web\NotFoundHttpException if the view file cannot be found
     * @throws \yii\base\InvalidConfigException
     */
    public function run($seq, $tag)
    {
        $this->controller->loadData($tag);

        $timings = $this->panel->calculateTimings();

        if (!isset($timings[$seq])) {
            throw new HttpException(404, 'Log message not found.');
        }

        $query = $timings[$seq]['info'];

        $results = $this->panel->getDb()->createCommand('EXPLAIN ' . $query)->queryAll();

        $output[] = '<table class="table"><thead><tr>' . implode(array_map(function ($key) {
                return '<th>' . $key . '</th>';
            }, array_keys($results[0]))) . '</tr></thead><tbody>';

        foreach ($results as $result) {
            $output[] = '<tr>' . implode(array_map(function ($value) {
                    return '<td>' . (empty($value) ? 'NULL' : htmlspecialchars($value)) . '</td>';
                }, $result)) . '</tr>';
        }
        $output[] = '</tbody></table>';
        return implode($output);
    }
}
