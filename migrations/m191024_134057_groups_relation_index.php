<?php

use yii\db\Migration;

/**
 * Class m191024_134057_groups_relation_index
 */
class m191024_134057_groups_relation_index extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('parent_id', 'rel_groups_groups', 'parent_id');
		$this->createIndex('child_id', 'rel_groups_groups', 'child_id');
		$this->createIndex('parent_id_child_id', 'rel_groups_groups', ['parent_id', 'child_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('parent_id', 'rel_groups_groups');
		$this->dropIndex('child_id', 'rel_groups_groups');
		$this->dropIndex('parent_id_child_id', 'rel_groups_groups');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m191024_134057_groups_relation_index cannot be reverted.\n";

		return false;
	}
	*/
}
