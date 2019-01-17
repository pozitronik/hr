<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m190117_075947_import_helpers
 */
class m190117_075947_import_helpers extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {

		$this->createTable('import_fos_users', [
			'id' => $this->primaryKey(),
			'name' => $this->string(),
			'remote' => $this->boolean()->defaultValue(false)->notNull(),
			'email_sigma' => $this->string(),
			'email_alpha' => $this->string(),
			'position_id' => $this->integer()->comment('key to position id'),
			'functional_block_id' => $this->integer()->comment('key to functional block id'),
			'division_level1_id' => $this->integer()->comment('key to division_level1 id'),
			'division_level2_id' => $this->integer()->comment('key to division_level2 id'),
			'division_level3_id' => $this->integer()->comment('key to division_level3 id'),
			'division_level4_id' => $this->integer()->comment('key to division_level4 id'),
			'division_level5_id' => $this->integer()->comment('key to division_level5 id'),
			'town_id' => $this->integer()->comment('key to town id')
		]);

		$this->createTable('import_fos_positions', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);

		$this->createTable('import_fos_functional_block', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);

		$this->createTable('import_fos_division_level1', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);
		$this->createTable('import_fos_division_level2', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);
		$this->createTable('import_fos_division_level3', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);
		$this->createTable('import_fos_division_level4', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);
		$this->createTable('import_fos_division_level5', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);

		$this->createTable('import_fos_functional_block_tribe', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);

		$this->createTable('import_fos_tribe', [
			'id' => $this->primaryKey(),
			'code' => $this->string(),
			'name' => $this->string(),
			'leader_id' => $this->integer()->comment('key to tribe leader id'),
			'leader_it_id' => $this->integer()->comment('key to tribe leader it id'),
		]);

		$this->createTable('import_fos_tribe_leader', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('key to user id')
		]);

		$this->createTable('import_fos_tribe_leader_it', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('key to user id')
		]);

		$this->createTable('import_fos_cluster_product', [
			'id' => $this->primaryKey(),
			'name' => $this->string(),
			'leader_id' => $this->integer()->comment('key to cluster product leader id'),
		]);

		$this->createTable('import_fos_cluster_product_leader', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('key to user id')
		]);

		$this->createTable('import_fos_command', [
			'id' => $this->primaryKey(),
			'code' => $this->string(),
			'name' => $this->string(),
			'type' => $this->string(),
			'cluster_id' => $this->integer()->notNull()->comment('key to cluster product id'),
			'owner_id' => $this->integer()->comment('key to product owner id')

		]);
		$this->createTable('import_fos_product_owner', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('key to user id')
		]);

		$this->createTable('import_fos_command_position', [
			'id' => $this->primaryKey(),
			'code' => $this->string(),
			'name' => $this->string(),
		]);
		$this->createTable('import_fos_chapter', [
			'id' => $this->primaryKey(),
			'code' => $this->string(),
			'name' => $this->string(),
			'leader_id' => $this->integer()->comment('key to chapter leader id'),
			'couch_id' => $this->integer()->comment('key to couch id'),
		]);
		$this->createTable('import_fos_chapter_leader', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('key to user id')
		]);
		$this->createTable('import_fos_chapter_couch', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('key to user id')
		]);

		$this->createTable('import_fos_town', [
			'id' => $this->primaryKey(),
			'name' => $this->string()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_fos_users');
		$this->dropTable('import_fos_positions');
		$this->dropTable('import_fos_functional_block');
		$this->dropTable('import_fos_division_level1');
		$this->dropTable('import_fos_division_level2');
		$this->dropTable('import_fos_division_level3');
		$this->dropTable('import_fos_division_level4');
		$this->dropTable('import_fos_division_level5');

		$this->dropTable('import_fos_functional_block_tribe');
		$this->dropTable('import_fos_tribe');
		$this->dropTable('import_fos_tribe_leader');
		$this->dropTable('import_fos_tribe_leader_it');

		$this->dropTable('import_fos_cluster_product');
		$this->dropTable('import_fos_cluster_product_leader');
		$this->dropTable('import_fos_command');
		$this->dropTable('import_fos_product_owner');

		$this->dropTable('import_fos_command_position');

		$this->dropTable('import_fos_chapter');
		$this->dropTable('import_fos_chapter_leader');
		$this->dropTable('import_fos_command_couch');

		$this->dropTable('import_fos_town');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190117_075947_import_helpers cannot be reverted.\n";

		return false;
	}
	*/
}
