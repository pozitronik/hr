<?php

use yii\db\Migration;

/**
 * Class m190221_123355_position_grades_rules
 */
class m190221_123355_position_grades_rules extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('grades_positions_rules', [
			'id' => $this->primaryKey(),
			'grade_id' => $this->integer()->notNull(),
			'position_id' => $this->integer()->notNull()
		]);

		$this->createIndex('grade_id', 'grades_positions_rules', 'grade_id');
		$this->createIndex('position_id', 'grades_positions_rules', 'position_id');
		$this->createIndex('grade_id_position_id', 'grades_positions_rules', ['grade_id', 'position_id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('grades_positions_rules');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190221_123355_position_grades_rules cannot be reverted.\n";

		return false;
	}
	*/
}
