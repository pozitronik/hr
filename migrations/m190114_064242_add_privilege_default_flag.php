<?php

use yii\db\Migration;

/**
 * Class m190114_064242_add_privilege_default_flag
 */
class m190114_064242_add_privilege_default_flag extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_privileges', 'default', $this->boolean()->defaultValue(false)->notNull()->comment('Привилегия применяется для всех пользователей в системе'));
		$this->createIndex('default', 'sys_privileges', 'default');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_privileges'.'default');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190114_064242_add_privilege_default_flag cannot be reverted.\n";

		return false;
	}
	*/
}
