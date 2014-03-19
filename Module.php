<?php

namespace yz;

use backend\base\Controller;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
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
class Module extends \yii\base\Module
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
        return '0.1';
    }

    /**
     * Name of the module
     * @return string
     */
    public function getName()
    {
        return \Yii::t('yz', 'Yz Module');
    }

    /**
     * Name of the author
     * @return string
     */
    public function getAuthor()
    {
        return \Yii::t('yz', 'Yz Team');
    }

    /**
     * Description of the module
     * @return string
     */
    public function getDescription()
    {
        return \Yii::t('yz', 'Yz Module Description');
    }

    /**
     * @return null|\yz\icons\Icon
     */
    public function getIcon()
    {
        return null;
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
        $moduleDescription = \Yii::t('yz', 'Access to the module "{module}"', [
            'module' => $this->getName(),
        ]);

        $moduleAuthItem = [
            $moduleAuthItemName => [$moduleDescription, []],
        ];

        foreach (FileHelper::findFiles($this->controllerPath, ['only' => ['*Controller.php']]) as $file) {
            $relativePath = basename($file);
            $controllerBaseClassName = substr($relativePath, 0, -4); // Removing .php
            $controllerName = substr($controllerBaseClassName, 0, -10); // Removing Controller
            $controllerClassName = ltrim($this->controllerNamespace . '\\' . $controllerBaseClassName);
            if (is_subclass_of($controllerClassName, Controller::className())) {
                $controllerAuthItemName = $controllerClassName;
                $controllerDescription = \Yii::t('yz', 'Access to the section "{module}/{controller}"', [
                    'controller' => $controllerName,
                    'module' => $this->getName(),
                ]);
                $controllerAuthItem = [
                    $controllerAuthItemName => [$controllerDescription, []],
                ];
                $moduleAuthItem[$moduleAuthItemName][1][] = $controllerAuthItemName;

                $actionsAuthItems = [];
                $ref = new \ReflectionClass($controllerClassName);
                foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                    if (preg_match('/^action([A-Z].*)$/', $method->getName(), $m)) {
                        $action = $m[1];
                        $actionAuthItemName = AuthManager::getOperationName($controllerClassName, $action);
                        $actionDescription = \Yii::t('yz', 'Access to the action "{module}/{controller}/{action}"', [
                            'action' => $action,
                            'controller' => $controllerName,
                            'module' => $this->getName(),
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