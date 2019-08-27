<?php

use yii\db\Migration;

/**
 * Class m190827_075308_ImportFosClusterProductLeaderIt
 */
class m190827_075308_ImportFosClusterProductLeaderIt extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_fos_cluster_product_leader_it', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('key to user id')
		]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_fos_cluster_product_leader_it');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190827_075308_ImportFosClusterProductLeaderIt cannot be reverted.\n";

		return false;
	}
	*/
}
