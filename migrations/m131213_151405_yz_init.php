<?php

use yii\db\Schema;

class m131213_151405_yz_init extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%module_settings}}', [
            'id' => Schema::TYPE_PK,
            'module' => Schema::TYPE_STRING . '(32) NOT NULL',
            'setting' => Schema::TYPE_STRING . '(128) NOT NULL',
            'value' => Schema::TYPE_TEXT,
        ], 'ENGINE=InnoDB CHARSET=utf8');
        $this->createIndex('module', '{{%module_settings}}', 'module');

        return true;
    }

    public function down()
    {
        $this->dropTable('{{%module_settings}}');
        return true;
    }
}
