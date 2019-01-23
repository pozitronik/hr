<?php

use yii\db\Migration;

/**
 * Class m190123_123508_fos_import_rename_column
 */
class m190123_123508_fos_import_rename_column extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameColumn('import_fos', 'user_id', 'user_tn');

		$this->renameColumn('import_fos', 'tribe_leader_id', 'tribe_leader_tn');
		$this->renameColumn('import_fos', 'tribe_leader_it_id', 'tribe_leader_it_tn');
		$this->renameColumn('import_fos', 'cluster_product_leader_id', 'cluster_product_leader_tn');
		$this->renameColumn('import_fos', 'chapter_leader_id', 'chapter_leader_tn');
		$this->renameColumn('import_fos', 'chapter_couch_id', 'chapter_couch_tn');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameColumn('import_fos', 'user_tn', 'user_id');

		$this->renameColumn('import_fos', 'tribe_leader_tn', 'tribe_leader_id');
		$this->renameColumn('import_fos', 'tribe_leader_it_tn', 'tribe_leader_it_id');
		$this->renameColumn('import_fos', 'cluster_product_leader_tn', 'cluster_product_leader_id');
		$this->renameColumn('import_fos', 'chapter_leader_tn', 'chapter_leader_id');
		$this->renameColumn('import_fos', 'chapter_couch_tn', 'chapter_couch_id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_123508_fos_import_rename_column cannot be reverted.\n";

		return false;
	}
	*/
}
