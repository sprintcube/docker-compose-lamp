<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\models\router;

use Yii;
use yii\base\Model;
use yii\rest\UrlRule as RestUrlRule;
use yii\web\GroupUrlRule;
use yii\web\UrlManager;
use yii\web\UrlRule as WebUrlRule;

/**
 * RouterRules model
 *
 * @author Paweł Brzozowski <pawel@positive.codes>
 * @since 2.1.14
 */
class RouterRules extends Model
{
    /**
     * @var bool whether pretty URL option has been enabled in UrlManager
     */
    public $prettyUrl = false;
    /**
     * @var bool whether strict parsing option has been enabled in UrlManager
     */
    public $strictParsing = false;
    /**
     * @var string global suffix set in UrlManager
     */
    public $suffix;
    /**
     * @var array logged rules.
     * ```php
     * [
     *  [
     *      'name' => rule name or its class (string),
     *      'route' => (string),
     *      'verb' => (array),
     *      'suffix' => (string),
     *      'mode' => 'parsing only', 'creation only', or null,
     *      'type' => 'REST', 'GROUP', or null,
     *  ]
     * ]
     * ```
     */
    public $rules = [];


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (Yii::$app->urlManager instanceof UrlManager) {
            $this->prettyUrl = Yii::$app->urlManager->enablePrettyUrl;
            $this->suffix = Yii::$app->urlManager->suffix;
            $this->strictParsing = Yii::$app->urlManager->enableStrictParsing;

            if ($this->prettyUrl) {
                foreach (Yii::$app->urlManager->rules as $rule) {
                    $this->scanRule($rule);
                }
            }
        }
    }

    /**
     * Scans rule for basic data.
     * @param $rule
     * @param null $type
     * @throws \ReflectionException
     */
    protected function scanRule($rule, $type = null)
    {
        $route = $verb = $suffix = $mode = null;

        if ($rule instanceof GroupUrlRule) {
            $this->scanGroupRule($rule);
        } elseif ($rule instanceof RestUrlRule) {
            $this->scanRestRule($rule);
        } else {
            if ($rule instanceof WebUrlRule) {
                switch ($rule->mode) {
                    case WebUrlRule::PARSING_ONLY:
                        $mode = 'parsing only';
                        break;
                    case WebUrlRule::CREATION_ONLY:
                        $mode = 'creation only';
                        break;
                    case null;
                        $mode = null;
                        break;
                    default:
                        $mode = 'unknown';
                }

                $name = $rule->name;
                $route = $rule->route;
                $verb = $rule->verb;
                $suffix = $rule->suffix;
            } else {
                $name = get_class($rule);
            }

            $this->rules[] = [
                'name' => $name,
                'route' => $route,
                'verb' => $verb,
                'suffix' => $suffix,
                'mode' => $mode,
                'type' => $type
            ];
        }
    }

    /**
     * Scans group rule's rules for basic data.
     * @param GroupUrlRule $groupRule
     * @throws \ReflectionException
     */
    protected function scanGroupRule($groupRule)
    {
        foreach ($groupRule->rules as $rule) {
            $this->scanRule($rule, 'GROUP');
        }
    }

    /**
     * Scans REST rule's rules for basic data.
     * @param RestUrlRule $restRule
     * @throws \ReflectionException
     */
    protected function scanRestRule($restRule)
    {
        $reflectionClass = new \ReflectionClass($restRule);
        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);
        $rulesGroups = $reflectionProperty->getValue($restRule);

        foreach ($rulesGroups as $rules) {
            foreach ($rules as $rule) {
                $this->scanRule($rule, 'REST');
            }
        }
    }
}
