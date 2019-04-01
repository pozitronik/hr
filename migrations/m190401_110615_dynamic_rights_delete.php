<?php

use yii\db\Migration;

/**
 * Class m190401_110615_dynamic_rights_delete
 */
class m190401_110615_dynamic_rights_delete extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_user_rights', 'deleted', $this->boolean()->notNull()->defaultValue(false));
		$this->createIndex('deleted', 'sys_user_rights', 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_user_rights', 'deleted');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190401_110615_dynamic_rights_delete cannot be reverted.\n";

		return false;
	}
	*/
}
