<?php

use yii\db\Migration;

/**
 * Class m190122_080557_remove_hr_user_id_from_helper_tables
 */
class m190122_080557_remove_hr_user_id_from_helper_tables extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('import_fos_chapter_couch', 'hr_user_id');
		$this->dropColumn('import_fos_chapter_leader', 'hr_user_id');
		$this->dropColumn('import_fos_cluster_product_leader', 'hr_user_id');
		$this->dropColumn('import_fos_product_owner', 'hr_user_id');
		$this->dropColumn('import_fos_tribe_leader', 'hr_user_id');
		$this->dropColumn('import_fos_tribe_leader_it', 'hr_user_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->addColumn('import_fos_chapter_couch', 'hr_user_id', $this->integer()->null());
		$this->addColumn('import_fos_chapter_leader', 'hr_user_id', $this->integer()->null());
		$this->addColumn('import_fos_cluster_product_leader', 'hr_user_id', $this->integer()->null());
		$this->addColumn('import_fos_product_owner', 'hr_user_id', $this->integer()->null());
		$this->addColumn('import_fos_tribe_leader', 'hr_user_id', $this->integer()->null());
		$this->addColumn('import_fos_tribe_leader_it', 'hr_user_id', $this->integer()->null());
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190122_080557_remove_hr_user_id_from_helper_tables cannot be reverted.\n";

		return false;
	}
	*/
}
