<?php

use yii\db\Migration;

/**
 * Class m190221_123324_salary_fork
 */
class m190221_123324_salary_fork extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('grades_salary', [
			'id' => $this->primaryKey(),
			'min' => $this->float()->null()->comment('Минимальный оклад'),
			'max' => $this->float()->null()->comment('Максимальный оклад'),
			'currency' => $this->integer()->null()->comment('Валюта')
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('grades_salary');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190221_123324_salary_fork cannot be reverted.\n";

		return false;
	}
	*/
}
