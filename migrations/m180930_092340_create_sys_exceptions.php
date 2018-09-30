<?php

use yii\db\Migration;

/**
 * Class m180930_092340_create_sys_exceptions
 */
class m180930_092340_create_sys_exceptions extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_exceptions', [
			'id' => $this->primaryKey(),
			'timestamp' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
			'user_id' => $this->integer(),
			'code' => $this->integer(),
			'file' => $this->string(),
			'line' => $this->integer(),
			'message' => $this->text(),
			'trace' => $this->text()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_exceptions');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m180930_092340_create_sys_exceptions cannot be reverted.\n";

		return false;
	}
	*/
}
