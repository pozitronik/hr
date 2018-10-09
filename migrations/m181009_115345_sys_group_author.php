<?php

use yii\db\Migration;

/**
 * Class m181009_115345_sys_group_author
 */
class m181009_115345_sys_group_author extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_groups', 'author', $this->integer()->null()->comment('id создателя')->after('comment'));
		$this->createIndex('author', 'sys_groups', 'author');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_groups','author');
		$this->dropIndex('author','sys_groups');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181009_115345_sys_group_author cannot be reverted.\n";

		return false;
	}
	*/
}
