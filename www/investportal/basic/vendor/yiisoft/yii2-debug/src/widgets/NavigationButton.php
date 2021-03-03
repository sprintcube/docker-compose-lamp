<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\debug\widgets;

use yii\base\Widget;
use yii\debug\Panel;
use yii\helpers\Html;

/**
 * Render button for navigation to previous or next request in debug panel
 * @since 2.0.11
 */
class NavigationButton extends Widget
{
    /** @var array */
    public $manifest;
    /** @var string */
    public $tag;
    /** @var string */
    public $button;
    /** @var Panel */
    public $panel;

    /** @var string */
    private $firstTag;
    /** @var string */
    private $lastTag;
    /** @var int */
    private $currentTagIndex;


    /**
     * @inheritDoc
     */
    public function beforeRun()
    {
        $manifestKeys = array_keys($this->manifest);
        $this->firstTag = reset($manifestKeys);
        $this->lastTag = end($manifestKeys);
        $this->currentTagIndex = array_search($this->tag, $manifestKeys);

        return parent::beforeRun();
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $method = "render{$this->button}Button";

        return $this->$method();
    }

    /**
     * @return string
     */
    private function renderPrevButton()
    {
        $needLink = $this->tag !== $this->firstTag;

        return Html::a(
            'Prev',
            $needLink ? $this->getRoute(-1) : '',
            ['class' => ['btn', 'btn-light', $needLink ? '' : 'disabled']]
        );
    }

    /**
     * @return string
     */
    private function renderNextButton()
    {
        $needLink = $this->tag !== $this->lastTag;

        return Html::a(
            'Next',
            $needLink ? $this->getRoute(1) : '',
            ['class' => ['btn', 'btn-light', $needLink ? '' : 'disabled']]
        );
    }

    /**
     * @param int $inc Direction
     * @return array
     */
    private function getRoute($inc)
    {
        return [
            'view',
            'panel' => $this->panel->id,
            'tag' => array_keys($this->manifest)[$this->currentTagIndex + $inc],
        ];
    }
}
