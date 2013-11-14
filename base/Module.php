<?php

namespace yz\base;

use yii\base\Module as YiiModule;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yz\admin\components\AuthManager;
use yz\admin\components\BackendController;

/**
 * Class Module implements basic class for all Yz modules
 * @property-read string $version
 * @property-read string $author 
 * @property-read string $name 
 * @property-read array $routes
 * @property-read array $adminMenu
 * @package yz\base
 */
class Module extends YiiModule
{
    /**
     * Position in the menu of administration panel
     * @var int
     */
    public $adminMenuOrder = 0;

    /**
     * Version of the module
     * @return string
     */
    public function getVersion()
    {
        return '0.0.1';
    }

    /**
     * Name of the author
     * @return string
     */
    public function getAuthor()
    {
        return \Yii::t('yz/module','Yz Team');
    }

    /**
     * Name of the module
     * @return string
     */
    public function getName()
    {
        return \Yii::t('yz/module','Yz Module');
    }

    /**
     * Returns the list of routes for current module. THis list should be in the following form
     * ~~~
     * [
     *  'prepend' => [
     *      // List of the routes to prepend
     *  ],
     *  'append' => [
     *      // List of the routes to append
     *  ],
     * ]
     * ~~~
     * @returns array
     */
    public function getRoutes()
    {
        return [];
    }

    /**
     * Returns menu items for administration panel in the following form:
     * ~~~
     * [
     *     'label' => 'Group Title',
     *     'iconv' => 'icon',
     *     'items' => [
     *         [
     *             'route' => 'absolute/route', // Route (or URL if string)
     *             'authItem' => 'someItem', // Will be used to determine whether user has access to the menu
     *                                       // item. Otherwise information from the route will be used
     *             'label' => 'My title',
     *             'icon' => 'icon',
     *         ],
     *         [
     *              'class' => '\yz\module\AdminMenuItem', // Points to class that will return above array
     *              'parameter1' => '...'
     *         ]
     *     ],
     * ]
     * ~~~
     * @return array
     */
    public function getAdminMenu()
    {
        return [];
    }

    /**
     * Returns the list of the backend operations that are allowed to be permitted to the user.
     * By default list is auto-discovered as all actions of controllers that are children of BackendController.
     * List has the following form:
     * ~~~
     * [
     *  'authItemName' => ['Description', ['children1', 'children2, ...]],
     * ]
     * ~~~
     * @returns array
     */
    public function getAuthItems()
    {
        $list = [];

        $moduleAuthItemName = $this->className();
        $moduleDescription = \Yii::t('admin','Access to the "{module}" module',[
            '{module}' => $this->getName(),
        ]);

        $moduleAuthItem = [
            $moduleAuthItemName => [$moduleDescription, []],
        ];

        foreach(FileHelper::findFiles($this->controllerPath,['only' => 'Controller.php']) as $file) {
            $relativePath = str_replace($this->controllerPath, '', $file);
            $controllerClassName = ltrim($this->controllerNamespace . '\\' . $relativePath);
            $controllerClassName = substr($controllerClassName, 0, -4); // Removing .php
            if(is_subclass_of($controllerClassName, BackendController::className())) {
                $controllerAuthItemName = $controllerClassName;
                $controllerDescription = \Yii::t('admin','Access to the "{controller} section of "{module}',[
                    '{controller}' => $controllerClassName,
                    '{module}' => $this->getName(),
                ]);
                $controllerAuthItem = [
                    $controllerAuthItemName => [$controllerDescription, []],
                ];
                $moduleAuthItem[$moduleAuthItemName][1][] = $controllerAuthItemName;

                $actionsAuthItems = [];
                $ref = new \ReflectionClass($controllerClassName);
                foreach($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                    if(preg_match('/^action(.+)$/',$method->getName(),$m)) {
                        $action = $m[1];
                        $actionAuthItemName = AuthManager::getOperationName($controllerClassName, $action);
                        $actionDescription = \Yii::t('admin', 'Access to the "{action}" action of "{section}" section in module "{module}"',[
                            '{action}' => $action,
                            '{controller}' => $controllerClassName,
                            '{module}' => $this->getName(),
                        ]);
                        $actionsAuthItems[$actionAuthItemName] = [$actionDescription, []];
                        $controllerAuthItem[$actionAuthItemName][1][] = $actionsAuthItems;
                    }
                }
                $list = array_merge($list, $controllerAuthItem, $actionsAuthItems);
            }
        }

        $list = array_merge($moduleAuthItem, $list);

        return $list;
    }
}