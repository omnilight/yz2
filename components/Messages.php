<?php

namespace yz\components;
use yii\base\Component;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;


/**
 * Class Messages
 * @package \yz\components
 */
class Messages extends Component
{
	const CATEGORY_INFO = 'info';
	const CATEGORY_ERROR = 'error';
	const CATEGORY_WARNING = 'warning';
	const CATEGORY_SUCCESS = 'success';

	/**
	 * List of message transports in the following form:
	 * ~~~
	 * [
	 *   'db' => DatabaseMessageTransport::className(),
	 * ]
	 * ~~~
	 * Note, that transport with the name 'session' is predefined by default
	 * @var array
	 */
	public $transports = [];
	/**
	 * Name of the default transport. 'Session' by default.
	 * @var string
	 */
	public $defaultTransport = 'session';

	public function init()
	{
		if (!isset($this->transports['session']))
			$this->transports['session'] = SessionTransport::className();

		parent::init();
	}

	/**
	 * @param string $message
	 * @param mixed $userKey
	 * @param string $transport
	 */
	public function error($message, $userKey = null, $transport = null)
	{
		$this->addMessage(self::CATEGORY_ERROR, $message, $userKey, $transport);
	}

	/**
	 * @param string $message
	 * @param mixed $userKey
	 * @param string $transport
	 */
	public function success($message, $userKey = null, $transport = null)
	{
		$this->addMessage(self::CATEGORY_SUCCESS, $message, $userKey, $transport);
	}

	/**
	 * @param string $message
	 * @param mixed $userKey
	 * @param string $transport
	 */
	public function info($message, $userKey = null, $transport = null)
	{
		$this->addMessage(self::CATEGORY_INFO, $message, $userKey, $transport);
	}

	/**
	 * @param string $message
	 * @param mixed $userKey
	 * @param string $transport
	 */
	public function warning($message, $userKey = null, $transport = null)
	{
		$this->addMessage(self::CATEGORY_WARNING, $message, $userKey, $transport);
	}

	/**
	 * @param string $category
	 * @param string $message
	 * @param mixed $userKey
	 * @param string $transport
	 */
	public function addMessage($category, $message, $userKey = null, $transport = null)
	{
		$this->getTransport($transport)->addMessage($category, $message, $userKey);
	}

	/**
	 * Indicates whether messages with specified category exists
	 * @param string $category
	 * @param null $userKey
	 * @param string $transport
	 * @return bool
	 */
	public function hasMessages($category, $userKey = null, $transport = null)
	{
		return $this->getTransport($transport)->hasMessages($category, $userKey);
	}

	/**
	 * Returns messages with specified category
	 * @param string $category
	 * @param mixed $userKey
	 * @param string $transport
	 * @return array
	 */
	public function getMessages($category, $userKey = null, $transport = null)
	{
		return $this->getTransport($transport)->getMessages($category, $userKey);
	}

	/**
	 * Returns all messages
	 * @param mixed $userKey
	 * @param string $transport
	 * @return array
	 */
	public function getAllMessages($userKey = null, $transport = null)
	{
		return $this->getTransport($transport)->getAllMessages($userKey);
	}

	/**
	 * Removes messages with specified category
	 * @param string $category
	 * @param mixed $userKey
	 * @param string $transport
	 * @return mixed
	 */
	public function removeMessages($category, $userKey = null, $transport = null)
	{
		return $this->getTransport($transport)->removeMessages($category, $userKey);
	}

	/**
	 * Removes all messages
	 * @param mixed $userKey
	 * @param string $transport
	 * @return mixed
	 */
	public function removeAllMessages($userKey = null, $transport = null)
	{
		return $this->getTransport($transport)->removeAllMessages($userKey);
	}

	/**
	 * @param $transport
	 * @return MessageTransportInterface
	 * @throws \yii\base\InvalidCallException
	 * @throws \yii\base\InvalidConfigException
	 */
	protected function getTransport($transport)
	{
		$transport = $transport ?: $this->defaultTransport;
		if (!isset($this->transports[$transport]))
			throw new InvalidCallException('Requested transport "'.$transport.'" is not defined');

		if (!is_object($this->transports[$transport]))
			$object = $this->transports[$transport] = \Yii::createObject($this->transports[$transport]);
		else
			$object = $this->transports[$transport];

		if (!($object instanceof MessageTransportInterface))
			throw new InvalidConfigException('Transport '.$transport.' must implement MessageTransportInterface');

		return $object;
	}
} 