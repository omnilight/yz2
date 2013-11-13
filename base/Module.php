<?php

namespace yz\base;

use yii\base\Module as YiiModule;

class Module extends YiiModule
{
    /**
     * Version of the module
     * @return string
     */
    public function getVersion()
    {
        return '0.0.1';
    }

    public function getName()
    {
        return \Yii::t('yz/module','Yz Module');
    }
}