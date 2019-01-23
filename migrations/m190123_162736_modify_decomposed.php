<?php

use yii\db\Migration;

/**
 * Class m190123_162736_modify_decomposed
 */
class m190123_162736_modify_decomposed extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameColumn('import_fos_decomposed', 'functional_block', 'functional_block_id');
		$this->renameColumn('import_fos_decomposed', 'division_level_1', 'division_level_1_id');
		$this->renameColumn('import_fos_decomposed', 'division_level_2', 'division_level_2_id');
		$this->renameColumn('import_fos_decomposed', 'division_level_3', 'division_level_3_id');
		$this->renameColumn('import_fos_decomposed', 'division_level_4', 'division_level_4_id');
		$this->renameColumn('import_fos_decomposed', 'division_level_5', 'division_level_5_id');
		$this->renameColumn('import_fos_decomposed', 'functional_block_tribe', 'functional_block_tribe_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameColumn('import_fos_decomposed', 'functional_block_id', 'functional_block');
		$this->renameColumn('import_fos_decomposed', 'division_level_1_id', 'division_level_1');
		$this->renameColumn('import_fos_decomposed', 'division_level_2_id', 'division_level_2');
		$this->renameColumn('import_fos_decomposed', 'division_level_3_id', 'division_level_3');
		$this->renameColumn('import_fos_decomposed', 'division_level_4_id', 'division_level_4');
		$this->renameColumn('import_fos_decomposed', 'division_level_5_id', 'division_level_5');
		$this->renameColumn('import_fos_decomposed', 'functional_block_tribe_id', 'functional_block_tribe');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_162736_modify_decomposed cannot be reverted.\n";

		return false;
	}
	*/
}
