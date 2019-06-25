<?php

use yii\db\Migration;

/**
 * Class m190625_064744_sys_user_position_type_override
 */
class m190625_064744_sys_user_position_type_override extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_users_position_type', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('Пользователь'),
			'position_type_id' => $this->integer()->notNull()->comment('Тип должности'),
		]);

		$this->createIndex('user_id', 'rel_users_position_type', 'user_id');
		$this->createIndex('position_type_id', 'rel_users_position_type', 'position_type_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_users_position_type');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190625_064744_sys_user_position_type_override cannot be reverted.\n";

		return false;
	}
	*/
}
