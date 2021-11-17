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
use yii\base\Model;
use yii\helpers\Inflector;
use yii\web\GroupUrlRule;
use yii\web\UrlManager;
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
            foreach ($details['actions'] as $actionName) {
                if ($actionName === '__ACTIONS__') {
                    $name = $controllerClass . '::actions()';
                    $route = $controller . '/[external-action]';
                    $rule = null;
                    $count = 0;
                } else {
                    $actionId = substr($actionName, 6);
                    $route = $controller . '/' . mb_strtolower(
                            trim(preg_replace('/\p{Lu}/u', '-\0', $actionId), '-'),
                            'UTF-8'
                        );
                    list($rule, $count) = $this->getMatchedCreationRule($route);
                    $name = $controllerClass . '::' . $actionName . '()';
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
     * @param \ReflectionClass $controller reflection of the controller
     * @return array all available action IDs with optional action class name (for external actions).
     */
    protected function getActions($controller)
    {
        $actions = [];

        $methods = $controller->getMethods();
        foreach ($methods as $method) {
            $name = $method->getName();
            if ($name === 'actions') {
                $actions[] = '__ACTIONS__';
            } elseif ($method->isPublic() && !$method->isStatic() && strncmp($name, 'action', 6) === 0) {
                $actions[] = $name;
            }
        }

        return $actions;
    }

    /**
     * Returns available controllers of a specified module.
     * @param \yii\base\Module $module the module instance
     * @return array the available controller IDs and their class names
     * @throws \ReflectionException
     */
    protected function getModuleControllers($module)
    {
        $prefix = $module instanceof Application ? '' : $module->getUniqueId() . '/';

        $controllers = [];

        $modules = $module->getModules();
        foreach ($modules as $id => $child) {
            if (($child = $module->getModule($id)) === null) {
                continue;
            }
            $moduleControllers = $this->getModuleControllers($child);
            foreach ($moduleControllers as $controllerId => $controllerClass) {
                $controllers[$controllerId] = $controllerClass;
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

                    $controllerId = Inflector::camel2id(substr(basename($file), 0, -14), '-', true);
                    if (!empty($dir)) {
                        $controllerId = $dir . '/' . $controllerId;
                    }
                    $controllers[$prefix . $controllerId] = $controllerClass;
                }
            }
        }

        // controllerMap takes precedence
        foreach ($module->controllerMap as $controllerId => $controllerConfig) {
            if (is_array($controllerConfig)) {
                if (isset($controllerConfig['class'])) {
                    $controllers[$prefix . $controllerId] = $controllerConfig['class'];
                } elseif (isset($controllerConfig['__class'])) {
                    $controllers[$prefix . $controllerId] = $controllerConfig['__class'];
                }
            } elseif (is_string($controllerConfig)) {
                $controllers[$prefix . $controllerId] = $controllerConfig;
            }
        }

        return $controllers;
    }

    /**
     * Returns all available application routes (non-console) grouped by the controller's name.
     * @return array
     * @throws \ReflectionException
     */
    protected function getAppRoutes()
    {
        $controllers = $this->getModuleControllers(Yii::$app);

        $appRoutes = [];
        foreach ($controllers as $controllerId => $controllerClass) {
            if (!class_exists($controllerClass)) {
                continue;
            }
            $class = new \ReflectionClass($controllerClass);
            if (
                $class->isAbstract()
                || (!$class->isSubclassOf('yii\web\Controller') && !$class->isSubclassOf('yii\rest\Controller'))
            ) {
                continue;
            }

            $actions = $this->getActions($class);
            if (count($actions) === 0) {
                continue;
            }
            $appRoutes[$controllerId] = [
                'class' => $controllerClass,
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
        if (Yii::$app->urlManager instanceof UrlManager && Yii::$app->urlManager->enablePrettyUrl) {
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
