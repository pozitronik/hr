<?php

use yii\db\Migration;

/**
 * Class m200129_084747_Decompose_tables
 */
class m200129_084747_Decompose_tables extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_targets_clusters', [
			'id' => $this->primaryKey(),
			'cluster_name' => $this->string()->notNull(),
			'domain' => $this->integer()->notNull(),
			'hr_group_id' => $this->integer()->null()
		]);

		$this->createTable('import_targets_commands', [
			'id' => $this->primaryKey(),
			'command_name' => $this->string()->notNull(),
			'command_id' => $this->string()->notNull(),
			'domain' => $this->integer()->notNull(),
			'hr_group_id' => $this->integer()->null()
		]);

		$this->createTable('import_targets_subinitiatives', [
			'id' => $this->primaryKey(),
			'initiative' => $this->string()->notNull(),
			'domain' => $this->integer()->notNull(),
			'hr_target_id' => $this->integer()->null()
		]);

		$this->createTable('import_targets_milestones', [
			'id' => $this->primaryKey(),
			'initiative_id' => $this->integer()->null(),
			'milestone' => $this->string()->notNull(),
			'domain' => $this->integer()->notNull(),
			'hr_target_id' => $this->integer()->null()
		]);

		$this->createTable('import_targets_targets', [
			'id' => $this->primaryKey(),
			'milestone_id' => $this->integer()->null(),
			'cluster_id' => $this->integer()->null(),
			'group_id' => $this->integer()->null(),
			'target' => $this->text()->notNull(),
			'domain' => $this->integer()->notNull(),
			'result_id' => $this->integer()->null(),
			'value' => $this->string()->null(),
			'period' => $this->string()->null(),
			'isYear' => $this->boolean()->notNull()->defaultValue(false),
			'isLK' => $this->boolean()->notNull()->defaultValue(false),
			'isLT' => $this->boolean()->notNull()->defaultValue(false),
			'isCurator' => $this->boolean()->notNull()->defaultValue(false),
			'comment' => $this->text()->null(),
			'hr_target_id' => $this->integer()->null()
		]);

		$this->createTable('import_targets_decomposed', [
			'id' => $this->primaryKey(),
			'cluster_id' => $this->integer()->null(),
			'command_id' => $this->integer()->null(),
			'initiative_id' => $this->integer()->null(),
			'milestone_id' => $this->integer()->null(),
			'target_id' => $this->integer()->null(),
			'domain' => $this->integer()->notNull()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_targets_decomposed');
		$this->dropTable('import_targets_targets');
		$this->dropTable('import_targets_milestones');
		$this->dropTable('import_targets_subinitiatives');
		$this->dropTable('import_targets_commands');
		$this->dropTable('import_targets_clusters');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200129_084747_Decompose_tables cannot be reverted.\n";

		return false;
	}
	*/
}
