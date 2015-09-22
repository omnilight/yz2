<?php

namespace yz;

use backend\base\Controller;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\rbac\Item;
use yz\admin\helpers\Rbac;

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
     *  'authItemName' => ['Description', type, ['children1', 'children2, ...]],
     * ]
     * ~~~
     * @returns array
     */
    public function getAuthItems()
    {
        $list = [];

        if (is_dir($this->controllerPath) == false)
            return $list;

        $moduleAuthItemName = Rbac::authItemName($this->className());
        $moduleDescription = \Yii::t('yz', 'Access to the module "{module}"', [
            'module' => $this->getName(),
        ]);

        $moduleAuthItem = [
            $moduleAuthItemName => [$moduleDescription, Item::TYPE_PERMISSION, []],
        ];

        foreach (FileHelper::findFiles($this->controllerPath, ['only' => ['*Controller.php']]) as $file) {
            $relativePath = ltrim(substr($file, strlen($this->controllerPath)), '\\/');
            $controllerBaseClassName = substr($relativePath, 0, -4); // Removing .php
            $controllerName = substr($controllerBaseClassName, 0, -10); // Removing Controller
            $controllerClassName = ltrim($this->controllerNamespace . '\\' . str_replace('/', '\\', $controllerBaseClassName));
            if (is_subclass_of($controllerClassName, Controller::className())) {
                $controllerAuthItemName = Rbac::authItemName($controllerClassName);
                $controllerDescription = \Yii::t('yz', 'Access to the section "{module}/{controller}"', [
                    'controller' => $controllerName,
                    'module' => $this->getName(),
                ]);
                $controllerAuthItem = [
                    $controllerAuthItemName => [$controllerDescription, Item::TYPE_PERMISSION, []],
                ];
                $moduleAuthItem[$moduleAuthItemName][2][] = $controllerAuthItemName;

                $controllerInstance = $this->createControllerByID(Inflector::camel2id($controllerName));
                $actions = array_keys($controllerInstance->actions());

                $ref = new \ReflectionClass($controllerClassName);
                $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);

                $actionsAuthItems = [];
                foreach (array_merge($actions, $methods) as $method) {
                    if (is_string($method))
                        $action = ucfirst($method);
                    else {
                        /** @var \ReflectionMethod $method */
                        if (!preg_match('/^action([A-Z].*)$/', $method->getName(), $m))
                            continue;
                        $action = $m[1];
                    }
                    $actionAuthItemName = Rbac::operationName($controllerClassName, $action);
                    $actionDescription = \Yii::t('yz', 'Access to the action "{module}/{controller}/{action}"', [
                        'action' => $action,
                        'controller' => $controllerName,
                        'module' => $this->getName(),
                    ]);
                    $actionsAuthItems[$actionAuthItemName] = [$actionDescription, Item::TYPE_PERMISSION, []];
                    $controllerAuthItem[$controllerAuthItemName][2][] = $actionAuthItemName;
                }

                $list = array_merge($list, $controllerAuthItem, $actionsAuthItems);
            }
        }

        $list = array_merge($moduleAuthItem, $list);

        return $list;
    }
}