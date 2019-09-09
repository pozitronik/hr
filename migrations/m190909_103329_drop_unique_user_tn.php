<?php

use yii\db\Migration;

/**
 * Class m190909_103329_drop_unique_user_tn
 */
class m190909_103329_drop_unique_user_tn extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropIndex('user_tn', 'import_fos_users');
		$this->createIndex('user_tn', 'import_fos_users', 'user_tn');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('user_tn', 'import_fos_users');
		$this->createIndex('user_tn', 'import_fos_users', 'user_tn', true);
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190909_103329_drop_unique_user_tn cannot be reverted.\n";

		return false;
	}
	*/
}
