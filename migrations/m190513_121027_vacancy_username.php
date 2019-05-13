<?php

use yii\db\Migration;

/**
 * Class m190513_121027_vacancy_username
 */
class m190513_121027_vacancy_username extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_vacancy', 'username', $this->string()->null()->after('teamlead'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_vacancy', 'username');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190513_121027_vacancy_username cannot be reverted.\n";

		return false;
	}
	*/
}
