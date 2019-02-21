<?php

use yii\db\Migration;

/**
 * Class m190221_085042_ref_user_positions_extend
 */
class m190221_085042_ref_user_positions_extend extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_user_position_types', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'color' => $this->string(255)->null(),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

		$this->createIndex('name', 'ref_user_position_types', 'name', true);
		$this->createIndex('color', 'ref_user_position_types', 'color');
		$this->createIndex('deleted', 'ref_user_position_types', 'deleted');

		$this->createTable('rel_ref_user_positions_types', [
			'id' => $this->primaryKey(),
			'position_id' => $this->integer()->notNull(),
			'position_type_id' => $this->integer()->notNull()
		]);

		$this->createIndex('position_id_position_type_id', 'rel_ref_user_positions_types', ['position_id', 'position_type_id'], true);
		$this->createIndex('position_id', 'rel_ref_user_positions_types', ['position_id']);
		$this->createIndex('position_type_id', 'rel_ref_user_positions_types', ['position_type_id']);

		$this->createTable('ref_user_position_branches', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'color' => $this->string(255)->null(),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

		$this->createIndex('name', 'ref_user_position_branches', 'name', true);
		$this->createIndex('color', 'ref_user_position_branches', 'color');
		$this->createIndex('deleted', 'ref_user_position_branches', 'deleted');

		$this->createTable('rel_ref_user_positions_branches', [
			'id' => $this->primaryKey(),
			'position_id' => $this->integer()->notNull(),
			'position_branch_id' => $this->integer()->notNull()
		]);

		$this->createIndex('position_id_position_branch_id', 'rel_ref_user_positions_branches', ['position_id', 'position_branch_id'], true);
		$this->createIndex('position_id', 'rel_ref_user_positions_branches', ['position_id']);
		$this->createIndex('position_branch_id', 'rel_ref_user_positions_branches', ['position_branch_id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_ref_user_positions_branches');
		$this->dropTable('rel_ref_user_positions_types');
		$this->dropTable('ref_user_position_types');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190221_085042_ref_user_positions_extend cannot be reverted.\n";

		return false;
	}
	*/
}
