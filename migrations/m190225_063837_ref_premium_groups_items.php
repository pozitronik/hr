<?php

use yii\db\Migration;

/**
 * Class m190225_063837_ref_premium_groups_items
 */
class m190225_063837_ref_premium_groups_items extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_grade_premium_group', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull()->comment('Название'),
			'color' => $this->string(256)->notNull()->comment('Цвет'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

		$this->createIndex('name', 'ref_grades_premium_group', 'name', true);

		$this->addColumn('grades_positions_rules', 'premium_group_id', $this->integer()->null());
		$this->createIndex('premium_group_id', 'grades_positions_rules', 'premium_group_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_grade_premium_group');
		$this->dropColumn('grades_positions_rules','premium_group_id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190225_063837_ref_premium_groups_items cannot be reverted.\n";

		return false;
	}
	*/
}
