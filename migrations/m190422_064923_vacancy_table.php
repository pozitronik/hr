<?php

use yii\db\Migration;

/**
 * Class m190422_064923_vacancy_table
 */
class m190422_064923_vacancy_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_vacancy', [
			'id' => $this->primaryKey(),
			'vacancy_id' => $this->integer()->null()->comment('Внешний ID вакансии'),
			'ticket_id' => $this->integer()->null()->comment('ID заявки на подбор'),
			'status' => $this->integer()->notNull()->defaultValue(0)->comment('Статус вакансии'),
			'group' => $this->integer()->notNull()->comment('Группа'),
			'name' => $this->string()->null()->comment('Опциональное название вакансии'),
			'location' => $this->integer()->null()->comment('Локация'),
			'recruiter' => $this->integer()->null()->comment('Рекрутер'),
			'employer' => $this->integer()->null()->comment('Нанимающий руководитель'),
			'position' => $this->integer()->notNull()->comment('Должность'),
			'role' => $this->integer()->null()->comment('Назначение/роль'),
			'teamlead' => $this->integer()->null()->comment('teamlead'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата заведения вакансии'),
			'close_date' => $this->dateTime()->null()->defaultValue(null)->comment('Дата закрытия вакансии'),
			'estimated_close_date' => $this->dateTime()->null()->defaultValue(null)->comment('Дата ожидаемого закрытия вакансии'),
			'daddy' => $this->integer()->notNull()->comment('Автор вакансии'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_vacancy');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190422_064923_vacancy_table cannot be reverted.\n";

		return false;
	}
	*/
}
