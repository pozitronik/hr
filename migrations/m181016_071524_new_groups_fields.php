<?php

use yii\db\Migration;

/**
 * Class m181016_071524_new_groups_fields
 */
class m181016_071524_new_groups_fields extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_groups', 'type', $this->integer()->null()->comment('Тип группы')->after('name'));
		$this->createIndex('type', 'sys_groups', 'type');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_groups', 'type');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181016_071524_new_groups_fields cannot be reverted.\n";

		return false;
	}
	*/
}
