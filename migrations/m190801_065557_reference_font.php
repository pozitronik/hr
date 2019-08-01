<?php

use yii\db\Migration;

/**
 * Class m190801_065557_reference_font
 */
class m190801_065557_reference_font extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ref_attributes_types', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_group_relation_types', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_group_types', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_locations', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_salary_premium_group', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_user_position_branches', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_user_position_types', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_user_positions', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_user_roles', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_vacancy_recruiters', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));
		$this->addColumn('ref_vacancy_statuses', 'textcolor', $this->string()->null()->defaultValue(null)->comment('Цвет текста'));

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_attributes_types', 'textcolor');
		$this->dropColumn('ref_group_relation_types', 'textcolor');
		$this->dropColumn('ref_group_types', 'textcolor');
		$this->dropColumn('ref_locations', 'textcolor');
		$this->dropColumn('ref_salary_premium_group', 'textcolor');
		$this->dropColumn('ref_user_position_branches', 'textcolor');
		$this->dropColumn('ref_user_position_types', 'textcolor');
		$this->dropColumn('ref_user_positions', 'textcolor');
		$this->dropColumn('ref_user_roles', 'textcolor');
		$this->dropColumn('ref_vacancy_recruiters', 'textcolor');
		$this->dropColumn('ref_vacancy_statuses', 'textcolor');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190801_065557_reference_textcolor cannot be reverted.\n";

		return false;
	}
	*/
}
