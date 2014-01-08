<?php

namespace yz\widgets;

use yii\web\AssetBundle;

class ActiveFormAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@yz/widgets/assets/activeForm';
	/**
	 * @inheritdoc
	 */
	public $css = [
		'form.css',
	];
}