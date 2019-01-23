<?php

use yii\db\Migration;

/**
 * Class m190123_113154_command_position_indexes
 */
class m190123_113154_command_position_indexes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('position_id', 'import_fos_command_position', 'position_id', 'true');
		$this->createIndex('domain', 'import_fos_command_position', 'domain');
		$this->createIndex('name', 'import_fos_command_position', 'name');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('position_id', 'import_fos_command_position');
		$this->dropIndex('domain', 'import_fos_command_position');
		$this->dropIndex('name', 'import_fos_command_position');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_113154_command_position_indexes cannot be reverted.\n";

		return false;
	}
	*/
}
