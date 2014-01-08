<?php

namespace yz\admin;


/**
 * Class Extension
 */
class Extension extends \yii\base\Extension
{
	public static function init()
	{
		parent::init();

		\Yii::$app->i18n->translations['yz'] = [
			'class' => 'yii\i18n\PhpMessageSource',
			'basePath' => '@yz/messages',
			'sourceLanguage' => 'en-US',
		];
	}

} 