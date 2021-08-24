<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models\router;

use Yii;
use yii\base\Application;
use yii\base\Controller;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\rest\Controller as RestController;
use yii\web\Controller as WebController;
use yii\web\GroupUrlRule;
use yii\web\UrlRule;

/**
 * ActionRoutes model
 *
 * @author Paweł Brzozowski <pawel@positive.codes>
 * @since 2.1.14
 */
class ActionRoutes extends Model
{
    /**
     * @var array scanned actions with matching routes
     */
    public $routes = [];


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $appRoutes = $this->getAppRoutes();
        foreach ($appRoutes as $controller => $details) {
            $controllerClass = $details['class'];
            foreach ($details['actions'] as $actionName => $actionClass) {
                $actionId = $actionName;
                if ($actionClass === null) {
                    $actionId = substr($actionName, 6);
                }
                $route = $controller . '/' . mb_strtolower(trim(preg_replace('/\p{Lu}/u', '-\0', $actionId), '-'), 'UTF-8');
                list($rule, $count) = $this->getMatchedCreationRule($route);

                if ($actionClass === null) {
                    $name = $controllerClass . '::' . $actionName . '()';
                } else {
                    $name = $controllerClass . '::actions()[' . $actionName . '] => ' . $actionClass;
                }
                $this->routes[$name] = [
                    'route' => $route,
                    'rule' => $rule,
                    'count' => $count
                ];
            }
        }

        if (count($this->routes)) {
            ksort($this->routes);
        }
    }

    /**
     * Validates if the given class is a valid web or REST controller class.
     * @param string $controllerClass
     * @return bool
     * @throws \ReflectionException
     */
    protected function validateControllerClass($controllerClass)
    {
        if (class_exists($controllerClass)) {
            $class = new \ReflectionClass($controllerClass);
            return !$class->isAbstract()
                && ($class->isSubclassOf('yii\web\Controller') || $class->isSubclassOf('yii\rest\Controller'));
        }

        return false;
    }

    /**
     * Returns all available actions of the specified controller.
     * @param Controller $controller the controller instance
     * @return array all available action IDs with optional action class name (for external actions).
     * @throws \ReflectionException
     */
    protected function getActions($controller)
    {
        $actions = [];

        $externalActions = $controller->actions();
        foreach ($externalActions as $id => $externalAction) {
            $actions[$id] = $externalAction['class'];
        }

        $class = new \ReflectionClass($controller);
        foreach ($class->getMethods() as $method) {
            $name = $method->getName();
            if (
                $name !== 'actions'
                && $method->isPublic()
                && !$method->isStatic()
                && strncmp($name, 'action', 6) === 0
            ) {
                $actions[$name] = null;
            }
        }

        return $actions;
    }

    /**
     * Returns available controllers of a specified module.
     * @param \yii\base\Module $module the module instance
     * @return array the available controller names
     * @throws \ReflectionException
     */
    protected function getModuleControllers($module)
    {
        $prefix = $module instanceof Application ? '' : $module->getUniqueId() . '/';

        $controllers = [];
        foreach (array_keys($module->controllerMap) as $id) {
            $controllers[] = $prefix . $id;
        }

        foreach ($module->getModules() as $id => $child) {
            if (($child = $module->getModule($id)) === null) {
                continue;
            }
            foreach ($this->getModuleControllers($child) as $controller) {
                $controllers[] = $controller;
            }
        }

        $controllerPath = $module->getControllerPath();
        if (is_dir($controllerPath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($controllerPath, \RecursiveDirectoryIterator::KEY_AS_PATHNAME)
            );
            $iterator = new \RegexIterator($iterator, '/.*Controller\.php$/', \RecursiveRegexIterator::GET_MATCH);
            foreach ($iterator as $matches) {
                $file = $matches[0];
                $relativePath = str_replace($controllerPath, '', $file);
                $class = strtr($relativePath, [
                    '/' => '\\',
                    '.php' => '',
                ]);
                $controllerClass = $module->controllerNamespace . $class;
                if ($this->validateControllerClass($controllerClass)) {
                    $dir = ltrim(pathinfo($relativePath, PATHINFO_DIRNAME), '\\/');

                    $controller = Inflector::camel2id(substr(basename($file), 0, -14), '-', true);
                    if (!empty($dir)) {
                        $controller = $dir . '/' . $controller;
                    }
                    $controllers[] = $prefix . $controller;
                }
            }
        }

        return $controllers;
    }

    /**
     * Returns all available application routes (non-console) grouped by the controller's name.
     * @return array
     * @throws \ReflectionException
     * @throws InvalidConfigException
     */
    protected function getAppRoutes()
    {
        $controllers = array_unique($this->getModuleControllers(Yii::$app));

        $appRoutes = [];
        foreach ($controllers as $controller) {
            $result = Yii::$app->createController($controller);
            if ($result === false || (!$result[0] instanceof WebController && !$result[0] instanceof RestController)) {
                continue;
            }
            $actions = $this->getActions($result[0]);
            if (count($actions) === 0) {
                continue;
            }
            $appRoutes[$controller] = [
                'class' => get_class($result[0]),
                'actions' => $actions
            ];
        }

        return $appRoutes;
    }

    /**
     * Returns the first rule's name that matched given route (for creation) with number of scanned rules.
     * @param string $route
     * @return array rule name (or null if not matched) and number of scanned rules
     */
    protected function getMatchedCreationRule($route)
    {
        $count = 0;
        if (Yii::$app->urlManager->enablePrettyUrl) {
            foreach (Yii::$app->urlManager->rules as $rule) {
                $count++;
                $url = $rule->createUrl(Yii::$app->urlManager, $route, []);
                if ($url !== false) {
                    return [$this->getRuleName($rule), $count];
                }
            }
        }

        return [null, $count];
    }

    private function getRuleName($rule)
    {
        $name = null;
        if ($rule instanceof UrlRule && $rule->getCreateUrlStatus() === UrlRule::CREATE_STATUS_SUCCESS) {
            $name = $rule->name;
        } elseif ($rule instanceof GroupUrlRule) {
            foreach ($rule->rules as $subrule) {
                $name = $this->getRuleName($subrule);
                if ($name !== null) {
                    break;
                }
            }
        }

        return $name;
    }
}
