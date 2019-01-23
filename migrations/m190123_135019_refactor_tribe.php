<?php

use yii\db\Migration;

/**
 * Class m190123_135019_refactor_tribe
 */
class m190123_135019_refactor_tribe extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameColumn('import_fos_tribe', 'id', 'tribe_id');
		$this->renameColumn('import_fos_tribe', 'pkey', 'id');
		$this->createIndex('tribe_id', 'import_fos_tribe', 'tribe_id', true);
		$this->createIndex('domain', 'import_fos_tribe', 'domain');
		$this->createIndex('leader_id', 'import_fos_tribe', 'leader_id');
		$this->createIndex('leader_it_id', 'import_fos_tribe', 'leader_it_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('leader_it_id', 'import_fos_tribe');
		$this->dropIndex('leader_id', 'import_fos_tribe');
		$this->dropIndex('domain', 'import_fos_tribe');
		$this->dropIndex('tribe_id', 'import_fos_tribe');
		$this->renameColumn('import_fos_tribe', 'id', 'pkey');
		$this->renameColumn('import_fos_tribe', 'tribe_id', 'id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_135019_refactor_tribe cannot be reverted.\n";

		return false;
	}
	*/
}
