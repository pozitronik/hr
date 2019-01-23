<?php

use yii\db\Migration;

/**
 * Class m190123_134552_refactor_cluster
 */
class m190123_134552_refactor_cluster extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameColumn('import_fos_cluster_product', 'id', 'cluster_id');
		$this->renameColumn('import_fos_cluster_product', 'pkey', 'id');
		$this->createIndex('cluster_id', 'import_fos_cluster_product', 'cluster_id', true);
		$this->createIndex('domain', 'import_fos_cluster_product', 'domain');
		$this->createIndex('leader_id', 'import_fos_cluster_product', 'leader_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('leader_id', 'import_fos_cluster_product');
		$this->dropIndex('domain', 'import_fos_cluster_product');
		$this->dropIndex('cluster_id', 'import_fos_cluster_product');
		$this->renameColumn('import_fos_cluster_product', 'id', 'pkey');
		$this->renameColumn('import_fos_cluster_product', 'cluster_id', 'id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_134552_refactor_cluster cannot be reverted.\n";

		return false;
	}
	*/
}
