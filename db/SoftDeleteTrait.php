<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 08.01.14
 * Time: 1:00
 */

namespace yz\db;

/**
 * Class SoftDeleteTrait
 * @package yz\db
 */
trait SoftDeleteTrait
{
	public static function softDeleteAttributes()
	{
		return [
			0 => ['is_deleted' => 0],
			1 => ['is_deleted' => 1],
		];
	}

	public function softDelete()
	{
		// TODO
	}

	public function softDeleteAll($condition = '', $params = [])
	{
		return self::updateAll(self::softDeleteAttributes(), $condition, $params);
	}
} 