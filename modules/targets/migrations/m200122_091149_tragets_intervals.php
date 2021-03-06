<?php

use yii\db\Migration;

/**
 * Class m200122_091149_tragets_intervals
 */
class m200122_091149_tragets_intervals extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_targets_intervals', [
			'id' => $this->primaryKey(),
			'target' => $this->integer()->notNull()->comment('id цели'),
			'comment' => $this->text()->null()->comment('Описание интервала'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата создания'),
			'start_date' => $this->dateTime()->notNull()->comment('Дата начала интервала'),
			'finish_date' => $this->dateTime()->notNull()->comment('Дата конца интервала'),
			'daddy' => $this->integer()->null()->comment('ID зарегистрировавшего пользователя'),
		]);

		$this->createIndex('target', 'sys_targets_intervals', 'target');
		$this->createIndex('daddy', 'sys_targets_intervals', 'daddy');
		$this->createIndex('start_date', 'sys_targets_intervals', 'start_date');
		$this->createIndex('finish_date', 'sys_targets_intervals', 'finish_date');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_targets_intervals');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200122_091149_tragets_intervals cannot be reverted.\n";

		return false;
	}
	*/
}
