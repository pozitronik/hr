<?php

use yii\db\Migration;

/**
 * Class m190226_115148_salary_to_ref
 */
class m190226_115148_salary_to_ref extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameTable('grades', 'ref_salary_grades');
		$this->renameTable('ref_grades_premium_groups', 'ref_salary_premium_group');
		$this->renameTable('grades_positions_rules', 'rel_grades_positions_rules');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameTable('ref_salary_grades', 'grades');
		$this->renameTable('ref_salary_premium_group', 'ref_grades_premium_groups');
		$this->renameTable('rel_grades_positions_rules', 'grades_positions_rules');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190226_115148_salary_to_ref cannot be reverted.\n";

		return false;
	}
	*/
}
