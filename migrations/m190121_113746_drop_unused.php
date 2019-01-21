<?php

use yii\db\Migration;

/**
 * Class m190121_113746_drop_unused
 */
class m190121_113746_drop_unused extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('import_fos_users', 'functional_block_id');
		$this->dropColumn('import_fos_users', 'division_level1_id');
		$this->dropColumn('import_fos_users', 'division_level2_id');
		$this->dropColumn('import_fos_users', 'division_level3_id');
		$this->dropColumn('import_fos_users', 'division_level4_id');
		$this->dropColumn('import_fos_users', 'division_level5_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->addColumn('import_fos_users', 'functional_block_id', $this->integer()->null());
		$this->addColumn('import_fos_users', 'division_level1_id', $this->integer()->null());
		$this->addColumn('import_fos_users', 'division_level2_id', $this->integer()->null());
		$this->addColumn('import_fos_users', 'division_level3_id', $this->integer()->null());
		$this->addColumn('import_fos_users', 'division_level4_id', $this->integer()->null());
		$this->addColumn('import_fos_users', 'division_level5_id', $this->integer()->null());
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190121_113746_drop_unused cannot be reverted.\n";

		return false;
	}
	*/
}
