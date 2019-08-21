<?php

use yii\db\Migration;

/**
 * Class m190821_140504_add_textcolor_indexes
 */
class m190821_140504_add_textcolor_indexes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('color', 'ref_vacancy_statuses', 'color');
		$this->createIndex('textcolor', 'ref_vacancy_statuses', 'textcolor');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('color', 'ref_vacancy_statuses');
		$this->dropIndex('textcolor', 'ref_vacancy_statuses');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190821_140504_add_textcolor_indexes cannot be reverted.\n";

		return false;
	}
	*/
}
