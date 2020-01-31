<?php

use yii\db\Migration;

/**
 * Class m200131_111351_drop_targets_intervals
 */
class m200131_111351_drop_targets_intervals extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropTable('sys_targets_intervals');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->createTable('sys_targets_intervals', [
			'id' => $this->primaryKey(),
			'target' => $this->integer()->notNull()->comment('id цели'),
			'comment' => $this->text()->null()->comment('Описание интервала'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата создания'),
			'start_date' => $this->dateTime()->null()->comment('Дата начала интервала'),
			'finish_date' => $this->dateTime()->null()->comment('Дата конца интервала'),
			'daddy' => $this->integer()->null()->comment('ID зарегистрировавшего пользователя'),
		]);

		$this->addColumn('sys_targets_intervals', 'start_quarter', $this->smallInteger()->null()->comment('Стартовый квартал'));
		$this->addColumn('sys_targets_intervals', 'finish_quarter', $this->smallInteger()->null()->comment('Финальный квартал'));
		$this->addColumn('sys_targets_intervals', 'year', $this->integer()->null()->comment('Год'));

		$this->createIndex('start_quarter', 'sys_targets_intervals', 'start_quarter');
		$this->createIndex('finish_quarter', 'sys_targets_intervals', 'finish_quarter');
		$this->createIndex('year', 'sys_targets_intervals', 'year');

		$this->createIndex('target', 'sys_targets_intervals', 'target');
		$this->createIndex('daddy', 'sys_targets_intervals', 'daddy');
		$this->createIndex('start_date', 'sys_targets_intervals', 'start_date');
		$this->createIndex('finish_date', 'sys_targets_intervals', 'finish_date');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200131_111351_drop_targets_intervals cannot be reverted.\n";

		return false;
	}
	*/
}
