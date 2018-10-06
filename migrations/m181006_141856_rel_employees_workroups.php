<?php

use yii\db\Migration;

/**
 * Class m181006_141856_rel_employees_workroups
 */
class m181006_141856_rel_employees_workroups extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_employees_workgroups', [
			'employee_id' => $this->integer()->notNull()->comment('Сотрудник'),
			'workgroup_id' => $this->integer()->notNull()->comment('Рабочая группа'),
			'employee_role_id' => $this->integer()->notNull()->comment('Роль сотрудника в группе')
		]);

		$this->createIndex('employee_id_workgroup_id', 'rel_employees_workgroups', ['employee_id', 'workgroup_id'], true);
		$this->createIndex('employee_id_employee_role_id', 'rel_employees_workgroups', ['employee_id', 'employee_role_id']);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_employees_workgroups');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181006_141856_rel_employees_workroups cannot be reverted.\n";

		return false;
	}
	*/
}
