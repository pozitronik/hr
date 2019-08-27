<?php

use yii\db\Migration;

/**
 * Class m190827_081150_ImportFosClusterProductLeaderIt
 */
class m190827_081150_ImportFosClusterProductLeaderIt extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('import_fos_cluster_product', 'leader_it_id', $this->integer()->comment('key to cluster product leader it id')->after('leader_id'));
		$this->createIndex('leader_it_id', 'import_fos_cluster_product', 'leader_it_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('import_fos_cluster_product', 'leader_it_id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190827_081150_ImportFosClusterProductLeaderIt cannot be reverted.\n";

		return false;
	}
	*/
}
