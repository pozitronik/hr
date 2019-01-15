<?php
declare(strict_types = 1);

namespace app\models\core;

use app\models\users\Users;
use Throwable;
use Yii;
use yii\base\Model;

/**
 * Class Service
 * @package app\models\core
 */
class Service extends Model {

	/**
	 * Сброс к заводским настройкам.
	 * Очистка всех таблиц с данными
	 * Очистка всех справочников.
	 * Всё в ноль.
	 * После очистки создаётся чистый административный аккаунт.
	 */
	public static function ResetDB():bool {
		$connection = Yii::$app->db;
//		$transaction = new Transaction([
//			'db' => $connection
//		]);
//		$transaction->begin();
		$tables = [
			'ref_group_relation_types',
			'ref_group_types',
			'ref_user_positions',
			'ref_user_roles',
			'rel_groups_groups',
			'rel_privileges_rights',
			'rel_users_attributes',
			'rel_users_groups',
			'rel_users_groups_roles',
			'rel_users_privileges',
			'sys_attributes',
			'sys_attributes_boolean',
			'sys_attributes_date',
			'sys_attributes_integer',
			'sys_attributes_percent',
			'sys_attributes_score',
			'sys_attributes_string',
			'sys_attributes_text',
			'sys_attributes_time',
			'sys_exceptions',
			'sys_groups',
			'sys_privileges',
			'sys_users',
			'sys_users_options'
		];

		try {
			foreach ($tables as $table) {
				$connection->createCommand("TRUNCATE TABLE $table")->execute();
				$connection->createCommand("ALTER TABLE $table AUTO_INCREMENT = 0")->execute();
			}
			$admin = new Users();
			$admin->createModel([
				'username' => 'admin',
				'login' => 'admin',
				'password' => 'admin',
				'email' => 'admin@localhost',
				'salt' => null,
				'deleted' => false
			]);
		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable $t) {
//			$transaction->rollBack();
			return false;
		}
//		$transaction->commit();
		return true;
	}

}