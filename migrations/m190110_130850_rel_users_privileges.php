<?php

use yii\db\Migration;

/**
 * Class m190110_130850_rel_users_privileges
 */
class m190110_130850_rel_users_privileges extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_users_privileges', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'privilege_id' => $this->integer()->notNull()
		]);

		$this->createIndex('user_id', 'rel_users_privileges', 'user_id');
		$this->createIndex('privilege_id', 'rel_users_privileges', 'privilege_id');
		$this->createIndex('user_id_privilege_id', 'rel_users_privileges', ['user_id', 'privilege_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_users_privileges');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190110_130850_rel_users_privileges cannot be reverted.\n";

		return false;
	}
	*/
}
