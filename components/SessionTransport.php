<?php

namespace yz\components;
use yii\base\Object;


/**
 * Class SessionTransport
 * @package \yz\components
 */
class SessionTransport extends Object implements MessageTransportInterface
{
	public $categoriesPrefix = '___flash-';

	/**
	 * Adds message with the specified category
	 * @param string $category
	 * @param string $message
	 * @param mixed $userKey If null - message will be added for current user (if possible), if false -
	 * message will be added for any user (if possible)
	 */
	public function addMessage($category, $message, $userKey = null)
	{
		\Yii::$app->session->setFlash($this->categoriesPrefix . $category, $message);
	}

	/**
	 * Indicates whether messages with specified category exists
	 * @param string $category
	 * @param null $userKey
	 * @return bool
	 */
	public function hasMessages($category, $userKey = null)
	{
		return \Yii::$app->session->hasFlash($this->categoriesPrefix . $category);
	}

	/**
	 * Returns messages with specified category
	 * @param string $category
	 * @param mixed $userKey
	 * @return array
	 */
	public function getMessages($category, $userKey = null)
	{
		return [\Yii::$app->session->getFlash($this->categoriesPrefix . $category)];
	}

	/**
	 * Returns all messages
	 * @param mixed $userKey
	 * @return array
	 */
	public function getAllMessages($userKey = null)
	{
		return [\Yii::$app->session->getAllFlashes()];
	}

	/**
	 * Removes messages with specified category
	 * @param string $category
	 * @param mixed $userKey
	 */
	public function removeMessages($category, $userKey = null)
	{
		\Yii::$app->session->removeFlash($this->categoriesPrefix . $category);
	}

	/**
	 * Removes all messages
	 * @param mixed $userKey
	 */
	public function removeAllMessages($userKey = null)
	{
		\Yii::$app->session->removeAllFlashes();
	}

} 