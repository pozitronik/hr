<?php

use app\helpers\ArrayHelper;
use app\helpers\Date;
use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m181006_151846_add_admin_profile
 */
class m181006_151846_add_admin_profile extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$hostname = ArrayHelper::getValue($_SERVER, 'COMPUTERNAME', 'localhost');

		$this->truncateTable('sys_users');
		$this->insert('sys_users', [
			'id' => 1,
			'username' => 'admin',
			'login' => 'admin',
			'password' => '91c12d15ccdd587d4949cfaa56d2e2d10377caca',
			'salt' => '7621147c8178b68c1a2bbd44ec50ecda43430e80',
			'email' => "admin@$hostname",
			'create_date' => Date::lcDate(),
			'comment' => 'Системный администратор'
		]);

		Console::output("Default system user => admin:admin");
		Console::input('Press any key to acknowledge');


	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->truncateTable('sys_users');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181006_151846_add_admin_profile cannot be reverted.\n";

		return false;
	}
	*/
}
