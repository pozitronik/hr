<?php

use yii\db\Migration;

/**
 * Class m200124_111523_multiple_groups
 */
class m200124_111523_multiple_groups extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('sys_targets', 'group_id');

		$this->createTable('rel_targets_groups', [
			'id' => $this->primaryKey(),
			'target_id' => $this->integer(),
			'group_id' => $this->integer()
		]);

		$this->createIndex('target_id_group_id', 'rel_targets_groups', ['target_id', 'group_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_targets_groups');

		$this->addColumn('sys_targets', 'group_id', $this->integer());
		$this->createIndex('group_id', 'sys_targets', 'group_id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200124_111523_multiple_groups cannot be reverted.\n";

		return false;
	}
	*/
}
