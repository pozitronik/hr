<?php

use yii\db\Migration;

/**
 * Class m190226_122657_salary_fork
 */
class m190226_122657_salary_fork extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropTable('grades_salary');

		$this->createTable('salary_fork', [
			'id' => $this->primaryKey(),
			'position_id' => $this->integer()->notNull()->comment('Должность'),
			'grade_id' => $this->integer()->notNull()->comment('Грейд'),
			'premium_group_id' => $this->integer()->null()->comment('Группа премирования'),
			'location_id' => $this->integer()->null()->comment('Локация'),
			'min' => $this->float()->null()->comment('Минимальный оклад'),
			'max' => $this->float()->null()->comment('Максимальный оклад'),
			'currency' => $this->integer()->null()->comment('Валюта')
		]);

		$this->createIndex('position_id', 'salary_fork', 'position_id');
		$this->createIndex('grade_id', 'salary_fork', 'grade_id');
		$this->createIndex('premium_group_id', 'salary_fork', 'premium_group_id');
		$this->createIndex('location_id', 'salary_fork', 'location_id');
		$this->createIndex('position_id_grade_id_premium_group_id_location_id', 'salary_fork', ['position_id', 'grade_id', 'premium_group_id', 'location_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->createTable('grades_salary', [
			'id' => $this->primaryKey(),
			'min' => $this->float()->null()->comment('Минимальный оклад'),
			'max' => $this->float()->null()->comment('Максимальный оклад'),
			'currency' => $this->integer()->null()->comment('Валюта')
		]);

		$this->dropTable('salary_fork');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190226_122657_salary_fork cannot be reverted.\n";

		return false;
	}
	*/
}
