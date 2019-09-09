<?php

use yii\db\Migration;

/**
 * Class m190909_085722_user_tn
 */
class m190909_085722_user_tn extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_users_identifiers', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('id пользователя'),
			'tn' => $this->string()->null()->defaultValue(null)->comment('Табельный номер')
		]);

		$this->createIndex('user_id', 'sys_users_identifiers', 'user_id', true);
		$this->createIndex('tn', 'sys_users_identifiers', 'tn', true);
		$this->createIndex('user_id_tn', 'sys_users_identifiers', ['user_id', 'tn'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_users_identifiers');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190909_085722_user_tn cannot be reverted.\n";

		return false;
	}
	*/
}
