<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\View;

/**
 * The Yii Debug Module provides the debug toolbar and debugger
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    const DEFAULT_IDE_TRACELINE = '<a href="ide://open?url=file://{file}&line={line}">{text}</a>';

    /**
     * @var array the list of IPs that are allowed to access this module.
     * Each array element represents a single IP filter which can be either an IP address
     * or an address with wildcard (e.g. 192.168.0.*) to represent a network segment.
     * The default value is `['127.0.0.1', '::1']`, which means the module can only be accessed
     * by localhost.
     */
    public $allowedIPs = ['127.0.0.1', '::1'];
    /**
     * @var array the list of hosts that are allowed to access this module.
     * Each array element is a hostname that will be resolved to an IP address that is compared
     * with the IP address of the user. A use case is to use a dynamic DNS (DDNS) to allow access.
     * The default value is `[]`.
     */
    public $allowedHosts = [];
    /**
     * @var callable A valid PHP callback that returns true if user is allowed to use web shell and false otherwise
     *
     * The signature is the following:
     *
     * function (Action|null $action) The action can be null when called from a non action context (like set debug header)
     *
     * @since 2.1.0
     */
    public $checkAccessCallback;
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'yii\debug\controllers';
    /**
     * @var LogTarget
     */
    public $logTarget;
    /**
     * @var array|Panel[] list of debug panels. The array keys are the panel IDs, and values are the corresponding
     * panel class names or configuration arrays. This will be merged with [[corePanels()]].
     * You may reconfigure a core panel via this property by using the same panel ID.
     * You may also disable a core panel by setting it to be false in this property.
     */
    public $panels = [];
    /**
     * @var string the name of the panel that should be visible when opening the debug panel.
     * The default value is 'log'.
     * @since 2.0.7
     */
    public $defaultPanel = 'log';
    /**
     * @var string the directory storing the debugger data files. This can be specified using a path alias.
     */
    public $dataPath = '@runtime/debug';
    /**
     * @var int the permission to be set for newly created debugger data files.
     * This value will be used by PHP [[chmod()]] function. No umask will be applied.
     * If not set, the permission will be determined by the current environment.
     * @since 2.0.6
     */
    public $fileMode;
    /**
     * @var int the permission to be set for newly created directories.
     * This value will be used by PHP [[chmod()]] function. No umask will be applied.
     * Defaults to 0775, meaning the directory is read-writable by owner and group,
     * but read-only for other users.
     * @since 2.0.6
     */
    public $dirMode = 0775;
    /**
     * @var int the maximum number of debug data files to keep. If there are more files generated,
     * the oldest ones will be removed.
     */
    public $historySize = 50;
    /**
     * @var int the debug bar default height, as a percentage of the total screen height
     * @since 2.1.1
     */
    public $defaultHeight = 50;
    /**
     * @var bool whether to enable message logging for the requests about debug module actions.
     * You normally do not want to keep these logs because they may distract you from the logs about your applications.
     * You may want to enable the debug logs if you want to investigate how the debug module itself works.
     */
    public $enableDebugLogs = false;
    /**
     * @var bool whether to disable IP address restriction warning triggered by checkAccess function
     * @since 2.0.14
     */
    public $disableIpRestrictionWarning = false;
    /**
     * @var bool whether to disable access callback restriction warning triggered by checkAccess function
     * @since 2.1.0
     */
    public $disableCallbackRestrictionWarning = false;
    /**
     * @var mixed the string with placeholders to be be substituted or an anonymous function that returns the trace line string.
     * The placeholders are {file}, {line} and {text} and the string should be as follows:
     *
     * `File: {file} - Line: {line} - Text: {text}`
     *
     * The signature of the anonymous function should be as follows:
     *
     * ```php
     * function($trace, $panel) {
     *     // compute line string
     *     return $line;
     * }
     * ```
     * @since 2.0.7
     */
    public $traceLine = self::DEFAULT_IDE_TRACELINE;
    /**
     * @var array used when the virtual, containerized, or remote debug trace paths don't correspond to the developers
     * local paths. Acts on the {file} portion for the `$traceLine` property.
     *
     * The array key is the environment's path, while the value is the local desired path.
     *
     * It will only map the first matched matched key.
     *
     * Example:
     *
     * ```php
     * [
     *     '/app' => '/home/user/project/app',
     * ]
     * ```
     *
     * Note that this will not change the displayed text, only the link url.
     *
     * @since 2.1.6
     */
    public $tracePathMappings = [];
    /**
     * @var string The [[UrlRule]] class to use for rules generated by this module.
     * @since 2.1.1
     */
    public $urlRuleClass = 'yii\web\UrlRule';
     /**
      * @var string|callable Page title could be a string or a callable function
      *
      * ```php
      * ...
      * 'pageTitle' => 'Custom Debug Title',
      * ...
      * // OR
      * 'pageTitle' => function($url) {
      *     $domain = getDomain($url);
      *     return $domain . ' debugger';
      * }
      * ```
      *
      * @since 2.1.1
      */
    public $pageTitle;
    /**
     * @var string Yii logo URL
     */
    private static $_yiiLogo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAMAAAANIilAAAAC7lBMVEUAAACl034Cb7HlcjGRyT/H34fyy5PxqlSfzjwQeb5PmtX71HAMdrWOxkDzmU3qcDSPx0HzhUGNxT+/2lX2olDmUy/Q1l+TyD7rgjq21k3ZRzDQ4GGFw0Ghzz6MwOkKdrTA2lTzzMVjo9mhzkCIxUPk1MLynU7qWS33vmbP1rm011Fwqsj123/r44tUltTyq1aCxEOo0EL1tFuCw0Npp9v7xGVHkM8Ddrza0pvC3FboczHmXSvE21h+wkRkpNHvjkS92FPW3avpeDT2t1zX5GefzUD6wGQReLtMltPN417oczPZ0L+62FF+tuJgqtXZUzNzrN3s4Y7n65y72FLwmk7xjESr0kYof8MQe8DY5Gc6jMnN32DoaDLbTiLulUo1hsni45vuwnIigMXC21dqq8vKzaaBt+XU4mUMd7wDdr7xlUrU4a7A2VTD0LbVx5vvpFP/0m9godp/tuTD0LVyrsfZVDUuhMjkPChsrMt3suK92VDd52oEc7un0EKjzj7D21e01EuSyD2fzDvH3Fqu0kcDdL641k+x00rmXy0EdLiayzzynU2XyTzxmUur0ETshD7lZDDvkUbtiUDrgTvqfjrkWS292FPujEKAuObQ4GH3vWH1slr0r1j0pVLulEiPxj7oeDRnptn4zWrM31/1t13A2lb1rFb1qVS72FKHw0CLxD/qdTfnazL4wGPJ3VzwpFLpcjKFveljo9dfn9ZbntUYfcEIdr35w2XyoFH0ok/pfDZ9tONUmNRPltJIj89Ais388IL85Hn82nL80W33uV72tFy611DxlUnujkSCwkGlz0DqeTnocDJ3r99yrN1Xm9RFjc42hsorgsYhgMQPer/81XD5yGbT4mTriD/lbS3laCvjTiluqN5NktAxhMf853v84He/2VTgVCnmVSg8h8sHcrf6633+3nb8zGr2xmR/wEGcyzt3r+T/6n7tm01tqNnfSCnfPyO4zLmFwkDVRDGOweLP1aX55nrZTTOaxdjuY9uiAAAAfHRSTlMABv7+9hAJ/vMyGP2CbV5DOA+NbyYeG/DV0sC/ubaonYN5blZRQT41MSUk/v797+zj49PR0MXEw8PDu6imppqYlpOGhYN+bldWVFJROjAM+fPy8fDw8O7t6+vp5+Lh4N7e3Nvb2NPQ0MW8urm2rqiimJKFg3t5amZTT0k1ewExHwAABPVJREFUSMed1Xc81HEYB/DvhaOUEe29995777333ntv2sopUTQ4F104hRBSl8ohldCwOqfuuEiKaPdfz/P7/u6Syuu+ff727vM8z+8bhDHNB3TrXI38V6p1fvSosLBwgICd1qx/5cqVT8jrl9c1Wlm2qmFdgbWq5X316lXKq5dxu+ouyNWePevo6JjVd6il9T/soUPe3t48tyI0LeqWlpbk5oJ1dXVVKpNCH/e1/NO2rXXy5CEI5Y+6EZomn0tLSlS50OuaFZQUGuojl7vXtii/VQMnp5MQPW/+C6tUXDFnfeTubm4utVv+fud3EPTIUdfXYZVKpQULxTp75sz5h4PK7C4wO8zFCT1XbkxHG/cdZuaLqXV5Afb0xYW2etxsPxfg73htbEUPBhgXDgoKCg30kbu58Pai8/SW+o3t7e0TExPBYzuObkyXFk7SAnYFnBQYyPeePn3R2fnEiZsWPO5y6pQ9JpHXgPlHWlcLxWiTAh/LqX3wAOlNiYTXRzGn8F9I5LUx/052aLWOWVnwgQMfu7u7UQu9t26FhISYcpObHMdwHstxcR2uAc1ZSlgYsJsL7kutRCKT+XeyxWMfxHAeykE7OQGm6ecIOInaF3grmPkEWn8vL3FXIfxEnWMY8FTD5GYjeNwK3pbSCDEsTC30ysCK79/3HQY/MTggICABOZRTbYYHo9WuSiMjvhi/EWf90frGe3q2JmR8Ts65cwEJCVAOGgc3a6bD1vOVRj5wLVwY7U2dvR/vGRy1BB7TsgMH/HKAQzfVZlZEF0sjwHgtLC7GbySjvWCjojYS0vjIEcpBH8WTmwmIPmON4GEChksXF8MnotYX7NuMDGkb0vbaEeQ50E11A1R67SOnUzsjlsjgzvHx8cFRQKUFvQmpd/kaaD+sPoiYrqyfvDY39QPYOMTU1F8shn09g98WSOPi4szbEBuPy8BRY7V9l3L/34VDy2AvsdgXLfTGmZun9yY1PTw8Ll+DwenWI0j52A6awWGJzNQLj0VtenpsbHshWZXpQasTYO6ZJuTPCC3WQjFeix5LKpWap8dqNJohZHgmaA5DtQ35e6wtNnXS4wwojn2jUSimkH2ZtBpxnYp+67ce1pX7xBkF1KrV+S3IHIrxYuNJxbEd2SM4qoDDim/5+THrSD09bmzIn5eRPTiMNmYqLM2PDUMblNabzaE5PwbSZowHPdi0tsTQmKxor1EXFcXEDKnJf6q9xOBMCPvyVQG6aDGZhw80x8ZwK1h5ISzsRwe1Wt2B1MPHPZgYnqa3b1+4gOUKhUl/sP0Z7ITJycmowz5q3oxrfMBvvYBh6O7ZKcnvqY7dZuPXR8hQvOXSJdQc/7hhTB8TBjs6Ivz6pezsbKobmggYbJWOT1ADT8HFGxKW9LwTjRp4CujbTHj007t37kRHhGP5h5Tk5K0MduLce0/vvoyOjoiIuH4ddMoeBrzz2WvUMDrMDvpDFQa89Pkr4KCBo+7OYEdFpqLGcqqbMuDVaZGpqc/1OjycYerKohtpkZFl9ECG4qoihxvA9aN3ZDlXL5GDXR7Vr56BZtlYcAOwnQMdHXRPlmdd2U5kh5gffRHL0GSUXR5gKBeJ0tIiZ1UmLKlqlydygHD1s8EyYYe8PBFMjulVhbClEdy6kohLVTaJGEYW4eBr6MhsY1fi0ggoe7a3a7d84O6J5L8iNOiX3U+uoa/p8UPtoQAAAABJRU5ErkJggg==';


    /**
     * @var array routes of AJAX requests to skip from being displayed in toolbar
     * @since 2.1.14
     */
    public $skipAjaxRequestUrl = [];

    /**
     * Returns the logo URL to be used in `<img src="`
     *
     * @return string the logo URL
     */
    public static function getYiiLogo()
    {
        return self::$_yiiLogo;
    }

    /**
     * Sets the logo URL to be used in `<img src="`
     *
     * @param string $logo the logo URL
     */
    public static function setYiiLogo($logo)
    {
        self::$_yiiLogo = $logo;
    }

    /**
     * {@inheritdoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dataPath = Yii::getAlias($this->dataPath);

        if (Yii::$app instanceof \yii\web\Application) {
            $this->initPanels();
        }
    }

    /**
     * Initializes panels.
     * @throws \yii\base\InvalidConfigException
     */
    protected function initPanels()
    {
        // merge custom panels and core panels so that they are ordered mainly by custom panels
        if (empty($this->panels)) {
            $this->panels = $this->corePanels();
        } else {
            $corePanels = $this->corePanels();
            foreach ($corePanels as $id => $config) {
                if (isset($this->panels[$id])) {
                    unset($corePanels[$id]);
                }
            }
            $this->panels = array_filter(array_merge($corePanels, $this->panels));
        }

        foreach ($this->panels as $id => $config) {
            if (is_string($config)) {
                $config = ['class' => $config];
            }
            $config['module'] = $this;
            $config['id'] = $id;
            $this->panels[$id] = Yii::createObject($config);
            if ($this->panels[$id] instanceof Panel && !$this->panels[$id]->isEnabled()) {
                unset($this->panels[$id]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        /* @var $app \yii\base\Application */
        $this->logTarget = $app->getLog()->targets['debug'] = new LogTarget($this);

        // delay attaching event handler to the view component after it is fully configured
        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
            $app->getResponse()->on(Response::EVENT_AFTER_PREPARE, [$this, 'setDebugHeaders']);
        });
        $app->on(Application::EVENT_BEFORE_ACTION, function () use ($app) {
            $app->getView()->on(View::EVENT_END_BODY, [$this, 'renderToolbar']);
        });

        $app->getUrlManager()->addRules([
            [
                'class' => $this->urlRuleClass,
                'route' => $this->id,
                'pattern' => $this->id,
                'normalizer' => false,
                'suffix' => false
            ],
            [
                'class' => $this->urlRuleClass,
                'route' => $this->id . '/<controller>/<action>',
                'pattern' => $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>',
                'normalizer' => false,
                'suffix' => false
            ]
        ], false);
    }

    /**
     * {@inheritdoc}
     * @throws \yii\base\InvalidConfigException
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        if (!$this->enableDebugLogs) {
            foreach ($this->get('log')->targets as $target) {
                $target->enabled = false;
            }
        }

        if (!parent::beforeAction($action)) {
            return false;
        }

        // do not display debug toolbar when in debug view mode
        Yii::$app->getView()->off(View::EVENT_END_BODY, [$this, 'renderToolbar']);
        Yii::$app->getResponse()->off(Response::EVENT_AFTER_PREPARE, [$this, 'setDebugHeaders']);

        if ($this->checkAccess($action)) {
            $this->resetGlobalSettings();
            return true;
        }

        if ($action->id === 'toolbar') {
            // Accessing toolbar remotely is normal. Do not throw exception.
            return false;
        }

        throw new ForbiddenHttpException('You are not allowed to access this page.');
    }

    /**
     * Setting headers to transfer debug data in AJAX requests
     * without interfering with the request itself.
     *
     * @param \yii\base\Event $event
     * @since 2.0.7
     */
    public function setDebugHeaders($event)
    {
        if (!$this->checkAccess()) {
            return;
        }
        $url = Url::toRoute([
            '/' . $this->id . '/default/view',
            'tag' => $this->logTarget->tag,
        ]);
        $event->sender->getHeaders()
            ->set('X-Debug-Tag', $this->logTarget->tag)
            ->set('X-Debug-Duration', number_format((microtime(true) - YII_BEGIN_TIME) * 1000 + 1))
            ->set('X-Debug-Link', $url);
    }

    /**
     * Resets potentially incompatible global settings done in app config.
     */
    protected function resetGlobalSettings()
    {
        Yii::$app->assetManager->bundles = [];
    }

    /**
     * Gets toolbar HTML
     * @since 2.0.7
     */
    public function getToolbarHtml()
    {
        $url = Url::toRoute([
            '/' . $this->id . '/default/toolbar',
            'tag' => $this->logTarget->tag,
        ]);

        if (!empty($this->skipAjaxRequestUrl)) {
            foreach ($this->skipAjaxRequestUrl as $key => $route) {
                $this->skipAjaxRequestUrl[$key] = Url::to($route);
            }
        }
        return '<div id="yii-debug-toolbar" data-url="' . Html::encode($url) . '" data-skip-urls="' . htmlspecialchars(json_encode($this->skipAjaxRequestUrl)) . '" style="display:none" class="yii-debug-toolbar-bottom"></div>';
    }

    /**
     * Renders mini-toolbar at the end of page body.
     *
     * @param \yii\base\Event $event
     * @throws \Throwable
     */
    public function renderToolbar($event)
    {
        if (!$this->checkAccess() || Yii::$app->getRequest()->getIsAjax()) {
            return;
        }

        /* @var $view View */
        $view = $event->sender;
        echo $view->renderDynamic('return Yii::$app->getModule("' . $this->id . '")->getToolbarHtml();');

        // echo is used in order to support cases where asset manager is not available
        echo '<style>' . $view->renderPhpFile(__DIR__ . '/assets/css/toolbar.css') . '</style>';
        echo '<script>' . $view->renderPhpFile(__DIR__ . '/assets/js/toolbar.js') . '</script>';
    }

    /**
     * Checks if current user is allowed to access the module
     * @param \yii\base\Action|null $action the action to be executed. May be `null` when called from
     * a non action context
     * @return bool if access is granted
     */
    protected function checkAccess($action = null)
    {
        $allowed = false;

        $ip = Yii::$app->getRequest()->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                $allowed = true;
                break;
            }
        }
        if ($allowed === false) {
            foreach ($this->allowedHosts as $hostname) {
                $filter = gethostbyname($hostname);
                if ($filter === $ip) {
                    $allowed = true;
                    break;
                }
            }
        }
        if ($allowed === false) {
            if (!$this->disableIpRestrictionWarning) {
                Yii::warning('Access to debugger is denied due to IP address restriction. The requesting IP address is ' . $ip, __METHOD__);
            }

            return false;
        }

        if ($this->checkAccessCallback !== null && call_user_func($this->checkAccessCallback, $action) !== true) {
            if (!$this->disableCallbackRestrictionWarning) {
                Yii::warning('Access to debugger is denied due to checkAccessCallback.', __METHOD__);
            }

            return false;
        }

        return true;
    }

    /**
     * @return array default set of panels
     */
    protected function corePanels()
    {
        return [
            'config' => ['class' => 'yii\debug\panels\ConfigPanel'],
            'request' => ['class' => 'yii\debug\panels\RequestPanel'],
            'router' => ['class' => 'yii\debug\panels\RouterPanel'],
            'log' => ['class' => 'yii\debug\panels\LogPanel'],
            'profiling' => ['class' => 'yii\debug\panels\ProfilingPanel'],
            'db' => ['class' => 'yii\debug\panels\DbPanel'],
            'event' => ['class' => 'yii\debug\panels\EventPanel'],
            'assets' => ['class' => 'yii\debug\panels\AssetPanel'],
            'mail' => ['class' => 'yii\debug\panels\MailPanel'],
            'timeline' => ['class' => 'yii\debug\panels\TimelinePanel'],
            'user' => ['class' => 'yii\debug\panels\UserPanel'],
            'dump' => ['class' => 'yii\debug\panels\DumpPanel'],
        ];
    }

    /**
     * {@inheritdoc}
     * @since 2.0.7
     */
    protected function defaultVersion()
    {
        $packageInfo = Json::decode(file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'composer.json'));
        $extensionName = $packageInfo['name'];
        if (isset(Yii::$app->extensions[$extensionName])) {
            return Yii::$app->extensions[$extensionName]['version'];
        }
        return parent::defaultVersion();
    }

    /**
     * @return string page title to be used in HTML
     * @since 2.1.1
     */
    public function htmlTitle()
    {
        if (is_string($this->pageTitle) && !empty($this->pageTitle)) {
           return $this->pageTitle;
        }

        if (is_callable($this->pageTitle)) {
            return call_user_func($this->pageTitle, Url::base(true));
        }

        return 'Yii Debugger';
    }
}
