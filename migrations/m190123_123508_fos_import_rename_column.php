<?php

use yii\db\Migration;

/**
 * Class m190123_123508_fos_import_rename_column
 */
class m190123_123508_fos_import_rename_column extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameColumn('import_fos', 'user_id', 'user_tn');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameColumn('import_fos', 'user_tn', 'user_id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_123508_fos_import_rename_column cannot be reverted.\n";

		return false;
	}
	*/
}
