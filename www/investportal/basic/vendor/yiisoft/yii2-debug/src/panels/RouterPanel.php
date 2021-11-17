<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\panels;

use Yii;
use yii\base\InlineAction;
use yii\debug\models\router\ActionRoutes;
use yii\debug\models\router\CurrentRoute;
use yii\debug\models\router\RouterRules;
use yii\debug\Panel;
use yii\log\Logger;

/**
 * RouterPanel provides a panel which displays information about routing process.
 *
 * @property array $categories Note that the type of this property differs in getter and setter. See
 * [[getCategories()]] and [[setCategories()]] for details.
 *
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 * @since 2.0.8
 */
class RouterPanel extends Panel
{
    /**
     * @var array
     */
    private $_categories = [
        'yii\web\UrlManager::parseRequest',
        'yii\web\UrlRule::parseRequest',
        'yii\web\CompositeUrlRule::parseRequest',
        'yii\rest\UrlRule::parseRequest'
    ];


    /**
     * @param string|array $values
     */
    public function setCategories($values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        $this->_categories = array_merge($this->_categories, $values);
    }

    /**
     * Listens categories of the messages.
     * @return array
     */
    public function getCategories()
    {
        return $this->_categories;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Router';
    }

    /**
     * {@inheritdoc}
     */
    public function getSummary()
    {
        return Yii::$app->view->render('panels/router/summary', ['panel' => $this]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDetail()
    {
        return Yii::$app->view->render('panels/router/detail', [
            'currentRoute' => new CurrentRoute($this->data),
            'routerRules' => new RouterRules(),
            'actionRoutes' => new ActionRoutes(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        if (Yii::$app->requestedAction) {
            if (Yii::$app->requestedAction instanceof InlineAction) {
                $action = get_class(Yii::$app->requestedAction->controller) . '::' . Yii::$app->requestedAction->actionMethod . '()';
            } else {
                $action = get_class(Yii::$app->requestedAction) . '::run()';
            }
        } else {
            $action = null;
        }
        return [
            'messages' => $this->getLogMessages(Logger::LEVEL_TRACE, $this->_categories),
            'route' => Yii::$app->requestedAction ? Yii::$app->requestedAction->getUniqueId() : Yii::$app->requestedRoute,
            'action' => $action,
        ];
    }
}
