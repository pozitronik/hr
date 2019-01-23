<?php

use yii\db\Migration;

/**
 * Class m190123_104739_fospositionsUpgrade
 */
class m190123_104739_fospositionsUpgrade extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('name', 'import_fos_positions', 'name', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('name', 'import_fos_positions');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_104739_fospositionsUpgrade cannot be reverted.\n";

		return false;
	}
	*/
}
