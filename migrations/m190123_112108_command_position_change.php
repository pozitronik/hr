<?php

use yii\db\Migration;

/**
 * Class m190123_112108_command_position_change
 */
class m190123_112108_command_position_change extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameColumn('import_fos_command_position', 'id', 'position_id');
		$this->renameColumn('import_fos_command_position', 'pkey', 'id');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameColumn('import_fos_command_position', 'id', 'pkey');
		$this->renameColumn('import_fos_command_position', 'position_id', 'id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_112108_command_position_change cannot be reverted.\n";

		return false;
	}
	*/
}
