<?php

use yii\db\Migration;

/**
 * Class m181006_140806_employee
 */
class m181006_140806_employee extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('employees', [
			'id' => $this->primaryKey(),
			'name' => $this->string('512')->comment('ФИО'),
			'deleted' => $this->boolean()->defaultValue(false)
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('employees');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181006_140806_employee cannot be reverted.\n";

		return false;
	}
	*/
}
