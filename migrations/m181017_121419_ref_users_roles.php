<?php

use yii\db\Migration;

/**
 * Class m181017_121419_ref_users_roles
 */
class m181017_121419_ref_users_roles extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_user_positions', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull()->comment('Название'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

		$this->createIndex('name', 'ref_user_positions', 'name', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_user_positions');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181017_121419_ref_users_roles cannot be reverted.\n";

		return false;
	}
	*/
}
