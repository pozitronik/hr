<?php

use yii\db\Migration;

/**
 * Class m190128_140027_additional_indexing
 */
class m190128_140027_additional_indexing extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('name', 'import_competency_users', 'name', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('name', 'import_competency_users');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190128_140027_additional_indexing cannot be reverted.\n";

		return false;
	}
	*/
}
