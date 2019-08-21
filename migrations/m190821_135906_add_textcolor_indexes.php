<?php

use yii\db\Migration;

/**
 * Class m190821_135906_add_textcolor_indexes
 */
class m190821_135906_add_textcolor_indexes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('textcolor', 'ref_user_position_types', 'textcolor');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('textcolor', 'ref_user_position_types');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190821_135906_add_textcolor_indexes cannot be reverted.\n";

		return false;
	}
	*/
}
