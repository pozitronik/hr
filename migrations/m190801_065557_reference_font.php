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
		$this->addColumn('ref_attributes_types', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_group_relation_types', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_group_types', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_locations', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_salary_premium_group', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_user_position_branches', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_user_position_types', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_user_positions', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_user_roles', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_vacancy_recruiters', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));
		$this->addColumn('ref_vacancy_statuses', 'font', $this->string()->null()->defaultValue(null)->comment('Параметры шрифта'));

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_attributes_types', 'font');
		$this->dropColumn('ref_group_relation_types', 'font');
		$this->dropColumn('ref_group_types', 'font');
		$this->dropColumn('ref_locations', 'font');
		$this->dropColumn('ref_salary_premium_group', 'font');
		$this->dropColumn('ref_user_position_branches', 'font');
		$this->dropColumn('ref_user_position_types', 'font');
		$this->dropColumn('ref_user_positions', 'font');
		$this->dropColumn('ref_user_roles', 'font');
		$this->dropColumn('ref_vacancy_recruiters', 'font');
		$this->dropColumn('ref_vacancy_statuses', 'font');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190801_065557_reference_font cannot be reverted.\n";

		return false;
	}
	*/
}
