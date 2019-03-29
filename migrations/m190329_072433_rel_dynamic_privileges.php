<?php

use yii\db\Migration;

/**
 * Class m190329_072433_rel_dynamic_privileges
 */
class m190329_072433_rel_dynamic_privileges extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_privileges_dynamic_rights', [
			'id' => $this->primaryKey(),
			'privilege' => $this->integer()->notNull()->comment('id набора привилегий'),
			'right' => $this->integer()->notNull()->comment('ID динамической привилегии')
		]);
		$this->createIndex('privilege_right', 'rel_privileges_dynamic_rights', ['privilege', 'right'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_privileges_dynamic_rights');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190329_072433_rel_dynamic_privileges cannot be reverted.\n";

		return false;
	}
	*/
}
