<?php

namespace yz;


/**
 * Class Extension
 */
class Extension extends \yii\base\Extension
{
    public static function bootstrap()
    {
        parent::bootstrap();

        \Yii::$app->i18n->translations['yz'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yz/messages',
            'sourceLanguage' => 'en-US',
        ];
    }

} 