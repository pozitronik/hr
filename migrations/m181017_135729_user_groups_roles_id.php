<?php

use yii\db\Migration;

/**
 * Class m181017_135729_user_groups_roles_id
 */
class m181017_135729_user_groups_roles_id extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('rel_users_groups', 'id', $this->primaryKey()->first());
		$this->dropColumn('rel_users_groups', 'user_role_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('rel_users_groups', 'id');
		$this->addColumn('rel_users_groups', 'user_role_id', $this->integer()->null());
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181017_135729_user_groups_roles_id cannot be reverted.\n";

		return false;
	}
	*/
}
