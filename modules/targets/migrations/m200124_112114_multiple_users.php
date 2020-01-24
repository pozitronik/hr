<?php

use yii\db\Migration;

/**
 * Class m200124_112114_multiple_users
 */
class m200124_112114_multiple_users extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_targets_users', [
			'id' => $this->primaryKey(),
			'target_id' => $this->integer(),
			'user_id' => $this->integer()
		]);

		$this->createIndex('target_id_user_id', 'rel_targets_users', ['target_id', 'user_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_targets_users');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200124_112114_multiple_users cannot be reverted.\n";

		return false;
	}
	*/
}
