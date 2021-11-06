<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m190506_093946_sys_log_table
 */
class m190506_093946_sys_log_table extends Migration {
	private const TABLE_NAME = 'sys_log';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable(self::TABLE_NAME, [
			'id' => $this->primaryKey(),
			'at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
			'user' => $this->integer()->defaultValue(null),
			'model' => $this->string(64)->null(),
			'model_key' => $this->integer()->null(),
			'old_attributes' => $this->json(),
			'new_attributes' => $this->json(),
		]);

		$this->createIndex('user', self::TABLE_NAME, 'user');
		$this->createIndex('model', self::TABLE_NAME, 'model');
		$this->createIndex('model_key', self::TABLE_NAME, 'model_key');
		$this->createIndex('model_model_key', self::TABLE_NAME, ['model', 'model_key']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable(self::TABLE_NAME);

	}
}
