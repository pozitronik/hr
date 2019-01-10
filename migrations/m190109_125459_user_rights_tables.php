<?php

use yii\db\Migration;

/**
 * Class m190109_125459_user_rights_tables
 */
class m190109_125459_user_rights_tables extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_privileges', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull()->comment('Название набора прав'),
			'daddy' => $this->integer()->null()->comment('id создателя'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата создания'),
			'deleted' => $this->boolean()->defaultValue(false)
		]);

		$this->createIndex('name', 'sys_privileges', 'name');
		$this->createIndex('deleted', 'sys_privileges', 'deleted');

		$this->createTable('rel_privileges_rights', [
			'id' => $this->primaryKey(),
			'privilege' => $this->integer()->notNull()->comment('id набора привилегий'),
			'right' => $this->string()->notNull()->comment('Класс, предоставляющий право')
		]);
		$this->createIndex('privilege_right', 'rel_privileges_rights', ['privilege', 'right'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_privileges');
		$this->dropTable('rel_privileges_rights');

	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190109_125459_user_rights_tables cannot be reverted.\n";

		return false;
	}
	*/
}
