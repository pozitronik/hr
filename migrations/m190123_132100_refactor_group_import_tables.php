<?php

use yii\db\Migration;

/**
 * Class m190123_132100_refactor_group_import_tables
 */
class m190123_132100_refactor_group_import_tables extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('name', 'import_fos_functional_block', 'name', true);
		$this->createIndex('domain', 'import_fos_functional_block', 'domain');
		$this->createIndex('name', 'import_fos_functional_block_tribe', 'name', true);
		$this->createIndex('domain', 'import_fos_functional_block_tribe', 'domain');
		$this->createIndex('name', 'import_fos_division_level1', 'name', true);
		$this->createIndex('domain', 'import_fos_division_level1', 'domain');
		$this->createIndex('name', 'import_fos_division_level2', 'name', true);
		$this->createIndex('domain', 'import_fos_division_level2', 'domain');
		$this->createIndex('name', 'import_fos_division_level3', 'name', true);
		$this->createIndex('domain', 'import_fos_division_level3', 'domain');
		$this->createIndex('name', 'import_fos_division_level4', 'name', true);
		$this->createIndex('domain', 'import_fos_division_level4', 'domain');
		$this->createIndex('name', 'import_fos_division_level5', 'name', true);
		$this->createIndex('domain', 'import_fos_division_level5', 'domain');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('name', 'import_fos_functional_block');
		$this->dropIndex('domain', 'import_fos_functional_block');
		$this->dropIndex('name', 'import_fos_functional_block_tribe');
		$this->dropIndex('domain', 'import_fos_functional_block_tribe');

		$this->dropIndex('name', 'import_fos_division_level1');
		$this->dropIndex('domain', 'import_fos_division_level1');
		$this->dropIndex('name', 'import_fos_division_level2');
		$this->dropIndex('domain', 'import_fos_division_level2');
		$this->dropIndex('name', 'import_fos_division_level3');
		$this->dropIndex('domain', 'import_fos_division_level3');
		$this->dropIndex('name', 'import_fos_division_level4');
		$this->dropIndex('domain', 'import_fos_division_level4');
		$this->dropIndex('name', 'import_fos_division_level5');
		$this->dropIndex('domain', 'import_fos_division_level5');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_132100_refactor_group_import_tables cannot be reverted.\n";

		return false;
	}
	*/
}
