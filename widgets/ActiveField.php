<?php

namespace yz\widgets;

use yii\helpers\Html;


/**
 * Class ActiveField
 */
class ActiveField extends \yii\widgets\ActiveField
{
    const GROUP_ADDON = 'input-group-addon';
    const GROUP_BTN = 'input-group-btn';

    /**
     * @var ActiveForm the form that this field is associated with.
     */
    public $form;
    /**
     * Defines sizes of columns for form in horizontal mode
     * @var array
     */
    public $horizontal = [];
    /**
     * Define sizes of elements for form in inline mode
     * @var array
     */
    public $inline = [];
    /**
     * Html to prepend field
     * @var string
     */
    public $prepend = '';
    /**
     * Html to append to field
     * @var string
     */
    public $append = '';

    public function init()
    {
        $this->horizontal = array_merge([
            'label' => 'col-sm-2',
            'input' => 'col-sm-7',
            'offset' => 'col-sm-offset-2 col-sm-10',
        ], $this->horizontal);

        $this->inline = array_merge([
            'label' => 'sr-only',
            'input' => 'col-sm-7',
            'offset' => 'col-sm-offset-2 col-sm-10',
        ], $this->inline);

        // Placeholder definition for inline forms
        if (!isset($this->inputOptions['placeholder']) && $this->form->type == ActiveForm::TYPE_INLINE) {
            $this->inputOptions['placeholder'] = Html::encode($this->model->getAttributeLabel($this->attribute));
        }
        parent::init();
    }

    public function render($content = null)
    {
        $this->adaptField();
        $this->processPrependAndAppend();
        return parent::render($content);
    }

    /**
     * @param string $html
     * @param string $class
     * @return $this
     */
    public function prepend($html, $class = self::GROUP_ADDON)
    {
        $this->prepend = Html::tag('span', $html, ['class' => $class]);
        return $this;
    }

    /**
     * @param string $html
     * @param string $class
     * @return $this
     */
    public function append($html, $class = self::GROUP_ADDON)
    {
        $this->append = Html::tag('span', $html, ['class' => $class]);
        return $this;
    }

    /**
     * Adapts field to the current form style
     */
    protected function adaptField()
    {
        // Settings for inline forms
        if ($this->form->type == ActiveForm::TYPE_INLINE) {
            Html::addCssClass($this->labelOptions, $this->inline['label']);
        }
        // Settings for horizontal forms
        if ($this->form->type == ActiveForm::TYPE_HORIZONTAL) {
            $inputWrapperOptions = [];
            if (isset($this->parts['{label}']) && $this->parts['{label}'] == '') {
                Html::addCssClass($inputWrapperOptions, $this->horizontal['offset']);
            } else {
                Html::addCssClass($this->labelOptions, $this->horizontal['label']);
                Html::addCssClass($inputWrapperOptions, $this->horizontal['input']);
            }
            $this->template = str_replace('{input}', Html::tag('div', '{input}', $inputWrapperOptions), $this->template);
        }
    }

    protected function processPrependAndAppend()
    {
        $input = '{input}';
        if ($this->prepend !== '') {
            $input = $this->prepend . $input;
        }
        if ($this->append !== '') {
            $input = $input . $this->append;
        }
        if ($input != '{input}') {
            $input = Html::tag('div', $input, ['class' => 'input-group']);
            $this->template = str_replace('{input}', $input, $this->template);
        }
    }
}