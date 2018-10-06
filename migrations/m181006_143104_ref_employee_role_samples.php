<?php

use yii\db\Migration;

/**
 * Class m181006_143104_ref_employee_role_samples
 */
class m181006_143104_ref_employee_role_samples extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->insert('ref_employee_roles', [
			'name' => 'Джуниор',
			'value' => 'Разработчик начального уровня'
		]);
		$this->insert('ref_employee_roles', [
			'name' => 'Мидл',
			'value' => 'Разработчик обычного уровня'
		]);
		$this->insert('ref_employee_roles', [
			'name' => 'Сеньор',
			'value' => 'Разработчик профессионального уровня'
		]);
		$this->insert('ref_employee_roles', [
			'name' => 'Тимлид',
			'value' => 'Разработчик элитного уровня'
		]);
		$this->insert('ref_employee_roles', [
			'name' => 'Системный архитектор',
			'value' => 'Босс разработчиков'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->truncateTable('ref_employee_roles');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181006_143104_ref_employee_role_samples cannot be reverted.\n";

		return false;
	}
	*/
}
