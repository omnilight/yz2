<?php

namespace yz\components;

use Yii;

/**
 * Class UrlManager
 * @package yz\components
 */
class UrlManager extends \yii\web\UrlManager
{
    /**
     * @inheritdoc
     */
    public $enablePrettyUrl = true;
    /**
     * @inheritdoc
     */
    public $showScriptName = false;
    /**
     * @var bool
     */
    public $loadRulesFromModules = true;

    /**
     * @inheritdoc
     */
    protected function compileRules()
    {
        if ($this->loadRulesFromModules) {
            Yii::trace("Fetching URL rules from modules", __CLASS__);

            $modules = \Yii::$app->getModules();

            $routes = [
                'prepend' => [],
                'append' => [],
            ];

            foreach ($modules as $id => $moduleConfig) {
                $module = \Yii::$app->getModule($id);
                if ($module instanceof \yz\Module) {
                    $moduleRoutes = $module->getRoutes();
                    if (isset($moduleRoutes['prepend']))
                        $routes['prepend'] = array_merge($routes['prepend'], $moduleRoutes['prepend']);
                    if (isset($moduleRoutes['append']))
                        $routes['append'] = array_merge($routes['append'], $moduleRoutes['append']);
                }
            }

            $this->rules = array_merge(
                $routes['prepend'],
                $this->rules,
                $routes['append']
            );
        }

        parent::compileRules();
    }


}