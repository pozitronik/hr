<?php

use yii\db\Migration;

/**
 * Class m181031_100637_users_settings_table
 */
class m181031_100637_users_settings_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_users_options', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->comment('System user id'),
			'option' => $this->string(32)->notNull()->comment('Option name'),
			'value' => $this->json()->null()->comment('Option value in JSON')
		]);

		$this->createIndex('user_id', 'sys_users_options', 'user_id');
		$this->createIndex('user_id_option', 'sys_users_options', ['user_id', 'option'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_users_options');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181031_100637_users_settings_table cannot be reverted.\n";

		return false;
	}
	*/
}
