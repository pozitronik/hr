<?php

use yii\db\Migration;

/**
 * Class m181009_100149_okkam
 * Наплодил сущностей, давай kiss
 */
class m181009_100149_okkam extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameTable('workgroups', 'sys_groups');
		$this->dropTable('rel_employees_workgroups');

		$this->createTable('rel_users_groups', [
			'user_id' => $this->integer()->notNull()->comment('Сотрудник'),
			'group_id' => $this->integer()->notNull()->comment('Рабочая группа'),
			'user_role_id' => $this->integer()->notNull()->comment('Роль сотрудника в группе')
		]);

		$this->createIndex('user_id_group_id', 'rel_users_groups', ['user_id', 'group_id'], true);
		$this->createIndex('user_id_user_role_id', 'rel_users_groups', ['user_id', 'user_role_id']);

		$this->dropTable('ref_employee_roles');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m181009_100149_okkam cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181009_100149_okkam cannot be reverted.\n";

		return false;
	}
	*/
}
