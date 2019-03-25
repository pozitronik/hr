<?php

use yii\db\Migration;

/**
 * Class m190325_091025_sys_log
 */
class m190325_091025_sys_log extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_log', [
			'id' => $this->primaryKey(),
			'at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
			'user' => $this->integer()->defaultValue(null),
			'model' => $this->string(64)->null(),
			'attributes' => $this->json()
		]);

		$this->createIndex('user', 'sys_log', 'user');
		$this->createIndex('model', 'sys_log', 'model');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_log');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190325_091025_sys_log cannot be reverted.\n";

		return false;
	}
	*/
}
