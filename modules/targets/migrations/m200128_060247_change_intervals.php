<?php

use yii\db\Migration;

/**
 * Class m200128_060247_change_intervals
 */
class m200128_060247_change_intervals extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('sys_targets_intervals', 'start_date', $this->dateTime()->null()->comment('Дата начала интервала'));
		$this->alterColumn('sys_targets_intervals', 'finish_date', $this->dateTime()->null()->comment('Дата начала интервала'));

		$this->addColumn('sys_targets_intervals', 'start_quarter', $this->smallInteger()->null()->comment('Стартовый квартал'));
		$this->addColumn('sys_targets_intervals', 'finish_quarter', $this->smallInteger()->null()->comment('Abyfkmysq квартал'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('sys_targets_intervals', 'start_date', $this->dateTime()->notNull()->comment('Дата начала интервала'));
		$this->alterColumn('sys_targets_intervals', 'finish_date', $this->dateTime()->notNull()->comment('Дата начала интервала'));

		$this->dropColumn('sys_targets_intervals', 'start_quarter');
		$this->dropColumn('sys_targets_intervals', 'finish_quarter');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200128_060247_change_intervals cannot be reverted.\n";

		return false;
	}
	*/
}
