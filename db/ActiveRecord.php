<?php

namespace yz\db;

use yii\db\ActiveRecord as YiiActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * Class ActiveRecord
 * @package yz\db
 */
class ActiveRecord extends YiiActiveRecord
{
    public static function tableName()
    {
        return '{{' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '}}';
    }

} 