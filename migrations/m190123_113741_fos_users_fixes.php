<?php

use yii\db\Migration;

/**
 * Class m190123_113741_fos_users_fixes
 */
class m190123_113741_fos_users_fixes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameColumn('import_fos_users', 'id', 'user_tn');
		$this->renameColumn('import_fos_users', 'pkey', 'id');
		$this->createIndex('user_tn', 'import_fos_users', 'user_tn', 'true');
		$this->createIndex('sd_id', 'import_fos_users', 'sd_id');
		$this->createIndex('name', 'import_fos_users', 'name');
		$this->createIndex('domain', 'import_fos_users', 'domain');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('user_tn', 'import_fos_users');
		$this->dropIndex('sd_id', 'import_fos_users');
		$this->dropIndex('name', 'import_fos_users');
		$this->dropIndex('domain', 'import_fos_users');
		$this->renameColumn('import_fos_users', 'id', 'pkey');
		$this->renameColumn('import_fos_users', 'user_tn', 'id');

	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_113741_fos_users_fixes cannot be reverted.\n";

		return false;
	}
	*/
}
