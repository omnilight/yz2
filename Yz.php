<?php

namespace yz;


/**
 * Class Yz contains some helper static methods, constants and so on
 * @package \yz
 */
class Yz
{
    const FLASH_INFO = '__flash-info';
    const FLASH_SUCCESS = '__flash-success';
    const FLASH_WARNING = '__flash-warning';
    const FLASH_ERROR = '__flash-error';

    public static function errorFlash($error)
    {
        \Yii::$app->session->setFlash(self::FLASH_ERROR, \Yii::t('yz', 'Error: {error}', ['error' => $error]));
    }
}