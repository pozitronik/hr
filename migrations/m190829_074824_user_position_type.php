<?php

use yii\db\Migration;

/**
 * Class m190829_074824_user_position_type
 */
class m190829_074824_user_position_type extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
//		$this->addColumn('sys_users', 'position_type', $this->integer()->null()->defaultValue(null)->after('position')->comment('Прямое определение типа позиции пользователя'));
//		$this->createIndex('position_type', 'sys_users', 'position_type');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
//		$this->dropColumn('sys_users', 'position_type');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190829_074824_user_position_type cannot be reverted.\n";

		return false;
	}
	*/
}
