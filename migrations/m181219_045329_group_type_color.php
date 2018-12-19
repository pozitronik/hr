<?php

use yii\db\Migration;

/**
 * Class m181219_045329_group_type_color
 */
class m181219_045329_group_type_color extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ref_group_types', 'color', $this->string(255)->null()->comment('Цветокод'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_group_types', 'color');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181219_045329_group_type_color cannot be reverted.\n";

		return false;
	}
	*/
}
