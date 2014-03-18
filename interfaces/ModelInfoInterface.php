<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.12.13
 * Time: 13:53
 */

namespace yz\interfaces;

/**
 * Interface ModelInfoInterface
 * @package yz\admin\models
 */
interface ModelInfoInterface
{
    /**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle();

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural();
} 