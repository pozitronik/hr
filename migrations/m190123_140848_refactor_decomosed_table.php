<?php

use yii\db\Migration;

/**
 * Class m190123_140848_refactor_decomosed_table
 */
class m190123_140848_refactor_decomosed_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m190123_140848_refactor_decomosed_table cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_140848_refactor_decomosed_table cannot be reverted.\n";

		return false;
	}
	*/
}
