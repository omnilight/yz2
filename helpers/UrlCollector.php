<?php

namespace yz\helpers;

use yii\helpers\VarDumper;
use yz\base\Module;


/**
 * Class UrlCollector
 * @package yz\helpers
 */
class UrlCollector
{
    public static function collect()
    {
        $modules = \Yii::$app->getModules();

        $routes = [
            'prepend' => [],
            'append' => [],
        ];

        foreach($modules as $id => $moduleConfig) {
            $module = \Yii::$app->getModule($id);
            if($module instanceof Module) {
                $moduleRoutes = $module->getRoutes();
                $routes['prepend'] = array_merge($routes['prepend'], $moduleRoutes['prepend']);
                $routes['append'] = array_merge($routes['append'], $moduleRoutes['append']);
            }
        }

        \Yii::$app->urlManager->rules = array_merge(
            $routes['prepend'],
            \Yii::$app->urlManager->rules,
            $routes['append']
        );

        VarDumper::dump(\Yii::$app->urlManager->rules);
        die();
    }
} 