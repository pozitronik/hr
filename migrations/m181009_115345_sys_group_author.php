<?php

use yii\db\Migration;

/**
 * Class m181009_115345_sys_group_daddy
 */
class m181009_115345_sys_group_author extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_groups', 'daddy', $this->integer()->null()->comment('id создателя')->after('comment'));
		$this->createIndex('daddy', 'sys_groups', 'daddy');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_groups','daddy');
		$this->dropIndex('daddy','sys_groups');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181009_115345_sys_group_daddy cannot be reverted.\n";

		return false;
	}
	*/
}
