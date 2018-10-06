<?php

use yii\db\Migration;

/**
 * Class m181006_142719_ref_employee_roles
 */
class m181006_142719_ref_employee_roles extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_employee_roles', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull()->comment('Название'),
			'value' => $this->string(512)->notNull()->comment('Описание'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_employee_roles');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181006_142719_ref_employee_roles cannot be reverted.\n";

		return false;
	}
	*/
}
