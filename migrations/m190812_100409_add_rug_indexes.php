<?php

use yii\db\Migration;

/**
 * Class m190812_100409_add_rug_indexes
 */
class m190812_100409_add_rug_indexes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('user_id', 'rel_users_groups', 'user_id');
		$this->createIndex('group_id', 'rel_users_groups', 'group_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('user_id', 'rel_users_groups');
		$this->dropIndex('group_id', 'rel_users_groups');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190812_100409_add_rug_indexes cannot be reverted.\n";

		return false;
	}
	*/
}
