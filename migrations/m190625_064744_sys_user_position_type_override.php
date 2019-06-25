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
		$this->createTable('rel_user_position_types', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('Пользователь'),
			'position_type_id' => $this->integer()->notNull()->comment('Тип должности'),
		]);

		$this->createIndex('user_id', 'rel_user_position_types', 'user_id');
		$this->createIndex('position_type_id', 'rel_user_position_types', 'position_type_id');
		$this->createIndex('user_id_position_type_id', 'rel_user_position_types', [
			'user_id',
			'position_type_id'
		], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_user_position_types');
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
