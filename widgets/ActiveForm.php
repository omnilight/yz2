<?php

namespace yz\widgets;

use Yii;
use yii\helpers\Html;


/**
 * Class ActiveForm
 * @package yz\widgets
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    const TYPE_INLINE = 'form-inline';
    const TYPE_VERTICAL = 'form-vertical';
    const TYPE_HORIZONTAL = 'form-horizontal';

    /**
     * Type of the form: TYPE_INLINE, TYPE_VERTICAL or TYPE_HORIZONTAL
     * @var string
     */
    public $type = self::TYPE_HORIZONTAL;

    public function init()
    {
        if (!isset($this->fieldConfig['class'])) {
            $this->fieldConfig['class'] = ActiveField::className();
        }

        Html::addCssClass($this->options, static::$_configuration[$this->type]['class']);

        parent::init();
    }

    public function run()
    {
        $view = $this->getView();
        ActiveFormAsset::register($view);
        parent::run();
    }

    /**
     * @inheritdoc
     * @return \yz\widgets\ActiveField
     */
    public function field($model, $attribute, $options = [])
    {
        return parent::field($model, $attribute, $options);
    }


    static protected $_configuration = [
        self::TYPE_INLINE => ['class' => 'form-inline',],
        self::TYPE_VERTICAL => ['class' => 'form-vertical',],
        self::TYPE_HORIZONTAL => ['class' => 'form-horizontal',],
    ];
}