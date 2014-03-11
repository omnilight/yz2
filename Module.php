<?php

namespace yz;

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

	public function __construct($id, $parent = null, $config = [])
	{
		if (isset(\Yii::$app->params['application-type'])) {
			$this->setViewPath($this->getBasePath() . DIRECTORY_SEPARATOR . 'views'
				. DIRECTORY_SEPARATOR . \Yii::$app->params['application-type']);
		}

		parent::__construct($id, $parent, $config);
	}

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
		$moduleDescription = \Yii::t('yz', 'Access to the module {module}', [
			'{module}' => $this->getName(),
		]);

		$moduleAuthItem = [
			$moduleAuthItemName => [$moduleDescription, []],
		];

		foreach (FileHelper::findFiles($this->controllerPath, ['only' => 'Controller.php']) as $file) {
			$relativePath = str_replace($this->controllerPath, '', $file);
			$controllerClassName = ltrim($this->controllerNamespace . '\\' . $relativePath);
			$controllerClassName = substr($controllerClassName, 0, -4); // Removing .php
			if (is_subclass_of($controllerClassName, BackendController::className())) {
				$controllerAuthItemName = $controllerClassName;
				$controllerDescription = \Yii::t('yz', 'Access to the section {module} / {controller}', [
					'{controller}' => $controllerClassName,
					'{module}' => $this->getName(),
				]);
				$controllerAuthItem = [
					$controllerAuthItemName => [$controllerDescription, []],
				];
				$moduleAuthItem[$moduleAuthItemName][1][] = $controllerAuthItemName;

				$actionsAuthItems = [];
				$ref = new \ReflectionClass($controllerClassName);
				foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
					if (preg_match('/^action(.+)$/', $method->getName(), $m)) {
						$action = $m[1];
						$actionAuthItemName = AuthManager::getOperationName($controllerClassName, $action);
						$actionDescription = \Yii::t('yz', 'Access to the action {module} / {action} / {controller}', [
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

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if ($this->controllerNamespace === null && isset(\Yii::$app->params['application-type'])) {
			$class = get_class($this);
			if (($pos = strrpos($class, '\\')) !== false) {
				$this->controllerNamespace = substr($class, 0, $pos) . '\\controllers\\'
					. \Yii::$app->params['application-type'];
			}
		}

		parent::init();
	}
}