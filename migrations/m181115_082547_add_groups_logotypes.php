<?php

use yii\db\Migration;

/**
 * Class m181115_082547_add_groups_logotypes
 */
class m181115_082547_add_groups_logotypes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_groups', 'logotype', $this->string(255)->null()->after('create_date')->comment('Logotype image'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_groups', 'logotype');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181115_082547_add_groups_logotypes cannot be reverted.\n";

		return false;
	}
	*/
}
