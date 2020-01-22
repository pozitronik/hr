<?php

use yii\db\Migration;

/**
 * Class m200122_082050_sys_targets
 */
class m200122_082050_sys_targets extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_targets', [
			'id' => $this->primaryKey(),
			'type' => $this->integer()->notNull()->comment('id типа цели'),
			'result' => $this->integer()->null()->comment('id оценки/результата'),
			'group_id' => $this->integer()->notNull(),
			'name' => $this->string(512)->notNull(),
			'interval' => $this->integer()->null()->comment('id уникального интервала этой цели'),
			'budget' => $this->integer()->null()->comment('id бюджета')
		]);

		$this->createIndex('type','sys_targets','type');
		$this->createIndex('result','sys_targets','result');
		$this->createIndex('group_id','sys_targets','group_id');
		$this->createIndex('interval','sys_targets','interval');
		$this->createIndex('budget','sys_targets','budget');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_targets');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200122_082050_sys_targets cannot be reverted.\n";

		return false;
	}
	*/
}
