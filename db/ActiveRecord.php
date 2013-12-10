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

    /**
     * Returns array of attributes that should be used as key, value and optional group
     * in {@see \yii\helpers\Html::map()} function. By default key is primary key, value
     * - class converted to string, group - not used.
     * @return array [key, value, group optional]
     */
    public static function mapAttributes()
    {
        return [static::primaryKey(), function($model, $default = null) {
            /** @var ActiveRecord $model */
            return (string)$model;
        }];
    }

    /**
     * @return \yz\db\ActiveQuery
     */
    public static function createQuery()
    {
        return new \yz\db\ActiveQuery(['modelClass' => get_called_class()]);
    }


    /**
     * Returns attributes values, ex.:
     * ~~~
     *   [
     *      'genre' => [
     *          'male' => \Yii::t('app','Male'),
     *          'female' => \Yii::t('app', 'Female'),
     *   ]
     * ~~~
     * @return array
     */
    public function attributeValues()
    {
        return [];
    }

    /**
     * Returns values for specified attribute, or throws an exception if passed attribute
     * is not found in {@see attributeValues()}
     * @param string $attribute
     * @return array
     * @throws \yii\base\Exception
     */
    public function getAttributeValues($attribute)
    {
        $values = $this->attributeValues();
        if(isset($values[$attribute])) {
            return $values[$attribute];
        } else {
            throw new Exception('Trying to get values for unknown attribute: '.$attribute);
        }
    }
} 