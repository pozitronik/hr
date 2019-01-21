<?php

use yii\db\Migration;

/**
 * Class m190121_091443_decomposition_to_database_keys
 */
class m190121_091443_decomposition_to_database_keys extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('import_fos_chapter', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_cluster_product', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_command', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_division_level1', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_division_level2', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_division_level3', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_division_level4', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_division_level5', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_functional_block_tribe', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_tribe', 'hr_group_id', $this->integer()->null()->comment('id группы в рабочей базе (соответствие, установленное при импорте)'));

		$this->addColumn('import_fos_chapter_couch', 'hr_user_id', $this->integer()->null()->comment('id пользователя в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_chapter_leader', 'hr_user_id', $this->integer()->null()->comment('id пользователя в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_cluster_product_leader', 'hr_user_id', $this->integer()->null()->comment('id пользователя в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_product_owner', 'hr_user_id', $this->integer()->null()->comment('id пользователя в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_tribe_leader', 'hr_user_id', $this->integer()->null()->comment('id пользователя в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_tribe_leader_it', 'hr_user_id', $this->integer()->null()->comment('id пользователя в рабочей базе (соответствие, установленное при импорте)'));
		$this->addColumn('import_fos_users', 'hr_user_id', $this->integer()->null()->comment('id пользователя в рабочей базе (соответствие, установленное при импорте)'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('import_fos_chapter', 'hr_group_id');
		$this->dropColumn('import_fos_cluster_product', 'hr_group_id');
		$this->dropColumn('import_fos_command', 'hr_group_id');
		$this->dropColumn('import_fos_division_level1', 'hr_group_id');
		$this->dropColumn('import_fos_division_level2', 'hr_group_id');
		$this->dropColumn('import_fos_division_level3', 'hr_group_id');
		$this->dropColumn('import_fos_division_level4', 'hr_group_id');
		$this->dropColumn('import_fos_division_level5', 'hr_group_id');
		$this->dropColumn('import_fos_functional_block_tribe', 'hr_group_id');
		$this->dropColumn('import_fos_tribe', 'hr_group_id');
		$this->dropColumn('import_fos_chapter_couch', 'hr_user_id');
		$this->dropColumn('import_fos_chapter_leader', 'hr_user_id');
		$this->dropColumn('import_fos_cluster_product_leader', 'hr_user_id');
		$this->dropColumn('import_fos_product_owner', 'hr_user_id');
		$this->dropColumn('import_fos_tribe_leader', 'hr_user_id');
		$this->dropColumn('import_fos_tribe_leader_it', 'hr_user_id');
		$this->dropColumn('import_fos_users', 'hr_user_id');
	}

}
