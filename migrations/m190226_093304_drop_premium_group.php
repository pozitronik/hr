<?php

use yii\db\Migration;

/**
 * Class m190226_093304_drop_premium_group
 */
class m190226_093304_drop_premium_group extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('grades_positions_rules', 'premium_group_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->addColumn('grades_positions_rules', 'premium_group_id', $this->integer()->null());
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190226_093304_drop_premium_group cannot be reverted.\n";

		return false;
	}
	*/
}
