<?php

use yii\db\Migration;

/**
 * Class m181010_101831_user_role_id_not_required
 */
class m181010_101831_user_role_id_not_required extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('rel_users_groups','user_role_id', $this->integer()->null());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('rel_users_groups','user_role_id', $this->integer()->notNull());
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181010_101831_user_role_id_not_required cannot be reverted.\n";

		return false;
	}
	*/
}
