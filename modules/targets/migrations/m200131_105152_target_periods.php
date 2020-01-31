<?php

use yii\db\Migration;

/**
 * Class m200131_105152_target_periods
 */
class m200131_105152_target_periods extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_targets_periods', [
			'id' => $this->primaryKey(),
			'target_id' => $this->integer()->notNull(),
			'q1' => $this->boolean()->defaultValue(false)->notNull(),
			'q2' => $this->boolean()->defaultValue(false)->notNull(),
			'q3' => $this->boolean()->defaultValue(false)->notNull(),
			'q4' => $this->boolean()->defaultValue(false)->notNull(),
			'is_year' => $this->boolean()->defaultValue(false)->notNull()
		]);

		$this->createIndex('target_id', 'sys_targets_periods', 'target_id', true);

		$this->createIndex('q1', 'sys_targets_periods', 'q1');
		$this->createIndex('q2', 'sys_targets_periods', 'q2');
		$this->createIndex('q3', 'sys_targets_periods', 'q3');
		$this->createIndex('q4', 'sys_targets_periods', 'q4');
		$this->createIndex('is_year', 'sys_targets_periods', 'is_year');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_targets_periods');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200131_105152_target_periods cannot be reverted.\n";

		return false;
	}
	*/
}
