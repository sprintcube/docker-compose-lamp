<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\gii\components;

use yii\gii\Generator;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * {@inheritdoc}
     */
    public $template = "{label}\n{input}\n{list}\n{error}";
    /**
     * @var Generator
     */
    public $model;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $stickyAttributes = $this->model->stickyAttributes();
        if (in_array($this->attribute, $stickyAttributes, true)) {
            $this->sticky();
        }
        $hints = $this->model->hints();
        if (isset($hints[$this->attribute])) {
            $this->hint($hints[$this->attribute]);
        }
        $autoCompleteData = $this->model->autoCompleteData();
        if (isset($autoCompleteData[$this->attribute])) {
            if (is_callable($autoCompleteData[$this->attribute])) {
                $this->autoComplete(call_user_func($autoCompleteData[$this->attribute]));
            } else {
                $this->autoComplete($autoCompleteData[$this->attribute]);
            }
        } else {
            $this->parts['{list}'] = '';
        }
    }

    /**
     * Makes field remember its value between page reloads
     * @return $this the field object itself
     */
    public function sticky()
    {
        Html::addCssClass($this->options, 'sticky');

        return $this;
    }

    /**
     * Makes field auto completable
     * @param array $data auto complete data (array of callables or scalars)
     * @return $this the field object itself
     */
    public function autoComplete($data)
    {
        $inputID = $this->getInputId();
        ArrayHelper::setValue($this->inputOptions, 'list', "$inputID-list");

        $html = Html::beginTag('datalist', ['id' => "$inputID-list"]) . "\n";
        foreach ($data as $item) {
            $html .= Html::tag('option', $item) . "\n";
        }
        $html .= Html::endTag('datalist');

        $this->parts['{list}'] = $html;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hint($content, $options = [])
    {
        Html::addCssClass($this->labelOptions, 'help');
        ArrayHelper::setValue($this->labelOptions, 'data.toggle', 'popover');
        ArrayHelper::setValue($this->labelOptions, 'data.content', $content);
        ArrayHelper::setValue($this->labelOptions, 'data.placement', 'right');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function checkbox($options = [], $enclosedByLabel = false)
    {
        $this->template = "{input}\n{label}\n{error}";
        Html::addCssClass($this->options, 'form-check');
        Html::addCssClass($options, 'form-check-input');
        Html::addCssClass($this->labelOptions, 'form-check-label');
        return parent::checkbox($options, $enclosedByLabel);
    }

    /**
     * {@inheritdoc}
     */
    public function radio($options = [], $enclosedByLabel = false)
    {
        $this->template = "{input}\n{label}\n{error}";
        Html::addCssClass($this->options, 'form-check');
        Html::addCssClass($options, 'form-check-input');
        Html::addCssClass($this->labelOptions, 'form-check-label');
        return parent::radio($options, $enclosedByLabel);
    }
}
