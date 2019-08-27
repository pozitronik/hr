<?php

use yii\db\Migration;

/**
 * Class m190827_072210_new_fos_columns
 */
class m190827_072210_new_fos_columns extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('import_fos', 'birthday', $this->string()->after('user_name')->comment('Дата рождения'));
		$this->addColumn('import_fos', 'cluster_product_leader_it_tn', $this->string()->after('cluster_product_leader_name')->comment('IT-лидер кластера/продукта ТН'));
		$this->addColumn('import_fos', 'cluster_product_leader_it_name', $this->string()->after('cluster_product_leader_it_tn')->comment('IT-лидер кластера/продукта'));
		$this->addColumn('import_fos', 'owner_tn', $this->string()->after('command_type')->comment('ТН владельца продукта'));
		$this->addColumn('import_fos', 'expert_area', $this->string()->after('command_position_name')->comment('Область экспертизы'));
		$this->addColumn('import_fos', 'combined_role', $this->string()->after('expert_area')->comment('Совмещаемая роль'));

		$this->createIndex('birthday', 'import_fos', 'birthday');
		$this->createIndex('cluster_product_leader_it_tn', 'import_fos', 'cluster_product_leader_it_tn');
		$this->createIndex('cluster_product_leader_it_name', 'import_fos', 'cluster_product_leader_it_name');
		$this->createIndex('owner_tn', 'import_fos', 'owner_tn');
		$this->createIndex('expert_area', 'import_fos', 'expert_area');
		$this->createIndex('combined_role', 'import_fos', 'combined_role');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('import_fos', 'birthday');
		$this->dropColumn('import_fos', 'cluster_product_leader_it_tn');
		$this->dropColumn('import_fos', 'cluster_product_leader_it_name');
		$this->dropColumn('import_fos', 'owner_tn');
		$this->dropColumn('import_fos', 'expert_area');
		$this->dropColumn('import_fos', 'combined_role');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190827_072210_new_fos_columns cannot be reverted.\n";

		return false;
	}
	*/
}
