<?php

use yii\db\Migration;

/**
 * Class m211117_150207_add_import_fos_cluster_product_leader_it_domain
 */
class m211117_150207_add_import_fos_cluster_product_leader_it_domain extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('import_fos_cluster_product_leader_it', 'domain', $this->integer());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('import_fos_cluster_product_leader_it', 'domain');
	}

}
