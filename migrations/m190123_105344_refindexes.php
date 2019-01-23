<?php

use yii\db\Migration;

/**
 * Class m190123_105344_refindexes
 */
class m190123_105344_refindexes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('name', 'import_fos_town', 'name', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('name', 'import_fos_town');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_105344_refindexes cannot be reverted.\n";

		return false;
	}
	*/
}
