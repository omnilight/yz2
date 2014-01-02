<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03.01.14
 * Time: 1:02
 */

namespace yz\components;

/**
 * Interface MessageTransportInterface
 * @package yz\components
 */
interface MessageTransportInterface
{
	/**
	 * Adds message with the specified category
	 * @param string $category
	 * @param string $message
	 * @param mixed $userKey If null - message will be added for current user (if possible), if false -
	 * message will be added for any user (if possible)
	 */
	public function addMessage($category, $message, $userKey = null);

	/**
	 * Indicates whether messages with specified category exists
	 * @param string $category
	 * @param null $userKey
	 * @return bool
	 */
	public function hasMessages($category, $userKey = null);

	/**
	 * Returns messages with specified category
	 * @param string $category
	 * @param mixed $userKey
	 * @return array
	 */
	public function getMessages($category, $userKey = null);

	/**
	 * Returns all messages
	 * @param mixed $userKey
	 * @return array
	 */
	public function getAllMessages($userKey = null);

	/**
	 * Removes messages with specified category
	 * @param string $category
	 * @param mixed $userKey
	 */
	public function removeMessages($category, $userKey = null);

	/**
	 * Removes all messages
	 * @param mixed $userKey
	 */
	public function removeAllMessages($userKey = null);
} 