<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\panels;

use Yii;
use yii\base\InvalidConfigException;
use yii\debug\Panel;
use yii\helpers\Html;
use yii\web\AssetBundle;

/**
 * Debugger panel that collects and displays asset bundles data.
 *
 * @author Artur Fursa <arturfursa@gmail.com>
 * @since 2.0
 */
class AssetPanel extends Panel
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Asset Bundles';
    }

    /**
     * {@inheritdoc}
     */
    public function getSummary()
    {
        return Yii::$app->view->render('panels/assets/summary', ['panel' => $this]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDetail()
    {
        return Yii::$app->view->render('panels/assets/detail', ['panel' => $this]);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $bundles = Yii::$app->view->assetManager->bundles;
        if (empty($bundles)) { // bundles can be false
            return [];
        }
        $data = [];
        foreach ($bundles as $name => $bundle) {
            if ($bundle instanceof AssetBundle) {
                $bundleData = (array)$bundle;
                if (isset($bundleData['publishOptions']['beforeCopy']) && $bundleData['publishOptions']['beforeCopy'] instanceof \Closure) {
                    $bundleData['publishOptions']['beforeCopy'] = '\Closure';
                }
                if (isset($bundleData['publishOptions']['afterCopy']) && $bundleData['publishOptions']['afterCopy'] instanceof \Closure) {
                    $bundleData['publishOptions']['afterCopy'] = '\Closure';
                }
                $data[$name] = $bundleData;
            }
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        try {
            isset(Yii::$app->view->assetManager) && Yii::$app->view->assetManager;
        } catch (InvalidConfigException $exception) {
            return false;
        }
        return true;
    }

    /**
     * Additional formatting for view.
     *
     * @param AssetBundle[] $bundles Array of bundles to formatting.
     *
     * @return AssetBundle[]
     */
    protected function format(array $bundles)
    {
        // @todo remove
        foreach ($bundles as $bundle) {
            array_walk($bundle->css, function (&$file, $key, $userData) {
                $file = Html::a($file, $userData->baseUrl . '/' . $file, ['target' => '_blank']);
            }, $bundle);

            array_walk($bundle->js, function (&$file, $key, $userData) {
                $file = Html::a($file, $userData->baseUrl . '/' . $file, ['target' => '_blank']);
            }, $bundle);

            array_walk($bundle->depends, function (&$depend) {
                $depend = Html::a($depend, '#' . $depend);
            });

            $this->formatOptions($bundle->publishOptions);
            $this->formatOptions($bundle->jsOptions);
            $this->formatOptions($bundle->cssOptions);
        }

        return $bundles;
    }

    /**
     * Format associative array of params to simple value.
     *
     * @param array $params
     *
     * @return array
     */
    protected function formatOptions(array &$params)
    {
        if (!is_array($params)) {
            return $params;
        }

        foreach ($params as $param => $value) {
            $params[$param] = Html::tag('strong', '\'' . $param . '\' => ') . (string)$value;
        }

        return $params;
    }
}
