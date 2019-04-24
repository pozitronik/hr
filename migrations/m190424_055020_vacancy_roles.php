<?php

use yii\db\Migration;

/**
 * Class m190424_055020_vacancy_roles
 */
class m190424_055020_vacancy_roles extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_vacancy_group_roles', [
			'id' => $this->primaryKey(),
			'vacancy_id' => $this->integer()->notNull(),
			'role_id' => $this->integer()->notNull()
		]);

		$this->createIndex('vacancy_id', 'rel_vacancy_group_roles', 'vacancy_id');
		$this->createIndex('role_id', 'rel_vacancy_group_roles', 'role_id');
		$this->createIndex('vacancy_id_role_id', 'rel_vacancy_group_roles', ['vacancy_id', 'role_id'], true);

		$this->dropColumn('sys_vacancy', 'role');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->addColumn('sys_vacancy', 'role', $this->integer()->null()->after('grade'));
		$this->dropTable('rel_vacancy_group_roles');

	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190424_055020_vacancy_roles cannot be reverted.\n";

		return false;
	}
	*/
}
