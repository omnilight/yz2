<?php

namespace yz\db;
use yii\base\Exception;
use yii\db\Connection;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;


/**
 * Class ActiveQuery
 * @package yz\db
 */
class ActiveQuery extends \yii\db\ActiveQuery
{
    public $asMap = false;
    /**
     * Set output format as map
     * @param bool $value
     * @return $this
     */
    public function asMap($value = true)
    {
        $this->asMap = $value;
        return $this;
    }

    public function one($db = null)
    {
        if($this->asMap) {
            throw new Exception('You are trying to call "one" method for ActiveQuery that is configured to return map');
        }

        return parent::one($db);
    }

    public function all($db = null)
    {
        if($this->asMap) {
            $attributes = call_user_func($this->modelClass, 'mapAttributes');
            return ArrayHelper::map(parent::all($db), $attributes[0], $attributes[1], isset($attributes[2])?$attributes[2]:null);
        }
        return parent::all($db);
    }


}