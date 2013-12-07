<?php

namespace yz\assets;

use yii\web\AssetBundle;

class YzAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@yz/assets/yzAsset';
    /**
     * @inheritdoc
     */
    public $js = [
        'yz.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}