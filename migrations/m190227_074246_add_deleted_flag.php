<?php

use yii\db\Migration;

/**
 * Class m190227_074246_add_deleted_flag
 */
class m190227_074246_add_deleted_flag extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('salary_fork', 'deleted', $this->boolean()->notNull()->defaultValue(false));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('salary_fork', 'deleted');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190227_074246_add_deleted_flag cannot be reverted.\n";

		return false;
	}
	*/
}
