<?php

namespace yz\db;

use yii\base\Exception;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * Class ActiveRecord is the base class for all DB models in Yz modules
 * @package yz\db
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * Declares the name of the database table associated with this AR class.
     * By default this method returns the class name as the table name that is equal to plurlized form
     * of class name with camel case converted to lower case with words concatenated with underscopes.
     * For example, 'Customer' becomes '{{%customers}}', and 'OrderItem' becomes
     * '{{%order_items}}'. You may override this method if the table is not named after this convention.
     * @return string the table name
     */
    public static function tableName()
    {
        return '{{%' . Inflector::tableize(StringHelper::basename(get_called_class())) . '}}';
    }
} 