<?php

use yii\db\Migration;

/**
 * Class m181017_141725_rel_user_groups_roles
 */
class m181017_141725_rel_user_groups_roles extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_user_groups_roles', [
			'user_group_id' => $this->integer()->notNull()->comment('ID связки пользователь/группа'),
			'role' => $this->integer()->notNull()->comment('Роль')
		]);

		$this->createIndex('user_group_id_role', 'rel_user_groups_roles', ['user_group_id', 'role'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_user_groups_roles');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181017_141725_rel_user_groups_roles cannot be reverted.\n";

		return false;
	}
	*/
}
