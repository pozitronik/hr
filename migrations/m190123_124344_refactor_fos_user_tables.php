<?php

use yii\db\Migration;

/**
 * Class m190123_124344_refactor_fos_user_tables
 */
class m190123_124344_refactor_fos_user_tables extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('user_id', 'import_fos_tribe_leader', 'user_id', 'true');
		$this->createIndex('domain', 'import_fos_tribe_leader', 'domain');
		$this->createIndex('user_id', 'import_fos_tribe_leader_it', 'user_id', 'true');
		$this->createIndex('domain', 'import_fos_tribe_leader_it', 'domain');
		$this->createIndex('user_id', 'import_fos_cluster_product_leader', 'user_id', 'true');
		$this->createIndex('domain', 'import_fos_cluster_product_leader', 'domain');
		$this->createIndex('user_id', 'import_fos_chapter_leader', 'user_id', 'true');
		$this->createIndex('domain', 'import_fos_chapter_leader', 'domain');
		$this->createIndex('user_id', 'import_fos_chapter_couch', 'user_id', 'true');
		$this->createIndex('domain', 'import_fos_chapter_couch', 'domain');
		$this->createIndex('user_id', 'import_fos_product_owner', 'user_id', 'true');
		$this->createIndex('domain', 'import_fos_product_owner', 'domain');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('user_id', 'import_fos_tribe_leader');
		$this->dropIndex('domain', 'import_fos_tribe_leader');
		$this->dropIndex('user_id', 'import_fos_tribe_leader_it');
		$this->dropIndex('domain', 'import_fos_tribe_leader_it');
		$this->dropIndex('user_id', 'import_fos_cluster_product_leader');
		$this->dropIndex('domain', 'import_fos_cluster_product_leader');
		$this->dropIndex('user_id', 'import_fos_chapter_leader');
		$this->dropIndex('domain', 'import_fos_chapter_leader');
		$this->dropIndex('user_id', 'import_fos_chapter_couch');
		$this->dropIndex('domain', 'import_fos_chapter_couch');
		$this->dropIndex('user_id', 'import_fos_product_owner');
		$this->dropIndex('domain', 'import_fos_product_owner');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_124344_refactor_fos_user_tables cannot be reverted.\n";

		return false;
	}
	*/
}
