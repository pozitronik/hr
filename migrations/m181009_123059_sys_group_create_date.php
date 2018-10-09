<?php

use yii\db\Migration;

/**
 * Class m181009_123059_sys_group_create_date
 */
class m181009_123059_sys_group_create_date extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_groups', 'create_date',  $this->dateTime()->notNull()->comment('Дата создания')->after('daddy'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_groups', 'create_date');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181009_123059_sys_group_create_date cannot be reverted.\n";

		return false;
	}
	*/
}
