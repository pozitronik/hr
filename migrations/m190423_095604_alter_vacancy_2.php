<?php

use yii\db\Migration;

/**
 * Class m190423_095604_alter_vacancy_2
 */
class m190423_095604_alter_vacancy_2 extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_vacancy', 'premium_group', $this->integer()->after('position')->null()->comment('Группа премирования'));
		$this->addColumn('sys_vacancy', 'grade', $this->integer()->null()->after('premium_group')->comment('Грейд'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_vacancy', 'premium_group');
		$this->dropColumn('sys_vacancy', 'grade');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190423_095604_alter_vacancy_2 cannot be reverted.\n";

		return false;
	}
	*/
}
