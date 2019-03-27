<?php

use yii\db\Migration;

/**
 * Class m190327_105459_dynamic_user_rights
 */
class m190327_105459_dynamic_user_rights extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_user_rights', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull()->comment('Название правила'),
			'rules' => $this->json()->notNull()->comment('Набор разрешений правила')
		]);

		$this->createIndex('name', 'sys_user_rights', 'name');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_user_rights');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190327_105459_dynamic_user_rights cannot be reverted.\n";

		return false;
	}
	*/
}
