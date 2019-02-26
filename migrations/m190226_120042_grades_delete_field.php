<?php

use yii\db\Migration;

/**
 * Class m190226_120042_grades_delete_field
 */
class m190226_120042_grades_delete_field extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ref_salary_grades', 'deleted', $this->boolean()->defaultValue(false)->notNull());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_salary_grades', 'deleted');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190226_120042_grades_delete_field cannot be reverted.\n";

		return false;
	}
	*/
}
