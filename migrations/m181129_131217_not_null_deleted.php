<?php

use yii\db\Migration;

/**
 * Class m181129_131217_not_null_deleted
 */
class m181129_131217_not_null_deleted extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->execute("UPDATE sys_users SET sys_users.deleted = 0 where sys_users.deleted is NULL");
		$this->execute("UPDATE sys_groups SET sys_groups.deleted = 0 where sys_groups.deleted is NULL");

		$this->execute("ALTER TABLE sys_groups CHANGE COLUMN deleted deleted TINYINT(1) NOT NULL DEFAULT 0");
		$this->execute("ALTER TABLE sys_users CHANGE COLUMN deleted deleted TINYINT(1) NOT NULL DEFAULT 0");
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m181129_131217_not_null_deleted cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181129_131217_not_null_deleted cannot be reverted.\n";

		return false;
	}
	*/
}
