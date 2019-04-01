<?php

use yii\db\Migration;

/**
 * Class m190401_073258_dynamic_rights_description
 */
class m190401_073258_dynamic_rights_description extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_user_rights', 'description', $this->string(255)->null()->comment('Описание правила')->after('name'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_user_rights', 'description');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190401_073258_dynamic_rights_description cannot be reverted.\n";

		return false;
	}
	*/
}
