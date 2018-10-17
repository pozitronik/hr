<?php

use yii\db\Migration;

/**
 * Class m181017_122521_user_role_column
 */
class m181017_122521_user_role_column extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_users', 'role', $this->integer()->null()->comment('Роль/должность/позиция')->after('deleted'));//Пишем прямо в конец, потом всё это утащим в отдельную таблицу
		$this->createIndex('role', 'sys_users', 'role');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_users', 'role');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181017_122521_user_role_column cannot be reverted.\n";

		return false;
	}
	*/
}
