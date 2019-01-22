<?php

use yii\db\Migration;

/**
 * Class m190122_143046_decomposed_index
 */
class m190122_143046_decomposed_index extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('position_id', 'import_fos_decomposed', 'position_id');
		$this->createIndex('user_id', 'import_fos_decomposed', 'user_id');
		$this->createIndex('functional_block', 'import_fos_decomposed', 'functional_block');
		$this->createIndex('division_level_1', 'import_fos_decomposed', 'division_level_1');
		$this->createIndex('division_level_2', 'import_fos_decomposed', 'division_level_2');
		$this->createIndex('division_level_3', 'import_fos_decomposed', 'division_level_3');
		$this->createIndex('division_level_4', 'import_fos_decomposed', 'division_level_4');
		$this->createIndex('division_level_5', 'import_fos_decomposed', 'division_level_5');
		$this->createIndex('functional_block_tribe', 'import_fos_decomposed', 'functional_block_tribe');
		$this->createIndex('tribe_id', 'import_fos_decomposed', 'tribe_id');
		$this->createIndex('cluster_product_id', 'import_fos_decomposed', 'cluster_product_id');
		$this->createIndex('command_id', 'import_fos_decomposed', 'command_id');
		$this->createIndex('command_position_id', 'import_fos_decomposed', 'command_position_id');
		$this->createIndex('chapter_id', 'import_fos_decomposed', 'chapter_id');
		$this->createIndex('domain', 'import_fos_decomposed', 'domain');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('position_id', 'import_fos_decomposed');
		$this->dropIndex('user_id', 'import_fos_decomposed');
		$this->dropIndex('functional_block', 'import_fos_decomposed');
		$this->dropIndex('division_level_1', 'import_fos_decomposed');
		$this->dropIndex('division_level_2', 'import_fos_decomposed');
		$this->dropIndex('division_level_3', 'import_fos_decomposed');
		$this->dropIndex('division_level_4', 'import_fos_decomposed');
		$this->dropIndex('division_level_5', 'import_fos_decomposed');
		$this->dropIndex('functional_block_tribe', 'import_fos_decomposed');
		$this->dropIndex('tribe_id', 'import_fos_decomposed');
		$this->dropIndex('cluster_product_id', 'import_fos_decomposed');
		$this->dropIndex('command_id', 'import_fos_decomposed');
		$this->dropIndex('command_position_id', 'import_fos_decomposed');
		$this->dropIndex('chapter_id', 'import_fos_decomposed');
		$this->dropIndex('domain', 'import_fos_decomposed');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190122_143046_decomposed_index cannot be reverted.\n";

		return false;
	}
	*/
}
