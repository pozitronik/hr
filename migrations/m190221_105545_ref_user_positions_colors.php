<?php

use yii\db\Migration;

/**
 * Class m190221_105545_ref_user_positions_colors
 */
class m190221_105545_ref_user_positions_colors extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ref_user_positions', 'color', $this->string()->null());
		$this->createIndex('color', 'ref_user_positions', 'color');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_user_positions', 'color');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190221_105545_ref_user_positions_colors cannot be reverted.\n";

		return false;
	}
	*/
}
