<?php

use yii\db\Migration;

/**
 * Class m190121_051347_add_domain_to_import_helpers
 */
class m190121_051347_add_domain_to_import_helpers extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('import_fos_chapter', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_chapter_couch', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_chapter_leader', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_cluster_product', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_cluster_product_leader', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_command', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_command_position', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_decomposed', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_division_level1', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_division_level2', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_division_level3', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_division_level4', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_division_level5', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_functional_block', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_functional_block_tribe', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_positions', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_product_owner', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_town', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_tribe', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_tribe_leader', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_tribe_leader_it', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
		$this->addColumn('import_fos_users', 'domain', $this->integer()->comment('Служебная метка домена импорта'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('import_fos_chapter', 'domain');
		$this->dropColumn('import_fos_chapter_couch', 'domain');
		$this->dropColumn('import_fos_chapter_leader', 'domain');
		$this->dropColumn('import_fos_cluster_product', 'domain');
		$this->dropColumn('import_fos_cluster_product_leader', 'domain');
		$this->dropColumn('import_fos_command', 'domain');
		$this->dropColumn('import_fos_command_position', 'domain');
		$this->dropColumn('import_fos_decomposed', 'domain');
		$this->dropColumn('import_fos_division_level1', 'domain');
		$this->dropColumn('import_fos_division_level2', 'domain');
		$this->dropColumn('import_fos_division_level3', 'domain');
		$this->dropColumn('import_fos_division_level4', 'domain');
		$this->dropColumn('import_fos_division_level5', 'domain');
		$this->dropColumn('import_fos_functional_block', 'domain');
		$this->dropColumn('import_fos_functional_block_tribe', 'domain');
		$this->dropColumn('import_fos_positions', 'domain');
		$this->dropColumn('import_fos_product_owner', 'domain');
		$this->dropColumn('import_fos_town', 'domain');
		$this->dropColumn('import_fos_tribe', 'domain');
		$this->dropColumn('import_fos_tribe_leader', 'domain');
		$this->dropColumn('import_fos_tribe_leader_it', 'domain');
		$this->dropColumn('import_fos_users', 'domain');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190121_051347_add_domain_to_import_helpers cannot be reverted.\n";

		return false;
	}
	*/
}
