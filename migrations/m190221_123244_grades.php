<?php

use yii\db\Migration;

/**
 * Class m190221_123244_grades
 */
class m190221_123244_grades extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('grades', [//Именую так, поскольку придумал именовать модульное именно так
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull()
		]);

		$this->createIndex('name', 'grades', 'name');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('grades');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190221_123244_grades cannot be reverted.\n";

		return false;
	}
	*/
}
