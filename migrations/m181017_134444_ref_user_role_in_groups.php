<?php

use yii\db\Migration;

/**
 * Class m181017_134444_ref_user_role_in_groups
 */
class m181017_134444_ref_user_role_in_groups extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_user_roles', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull()->comment('Название'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

		$this->createIndex('name', 'ref_user_roles', 'name', true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_user_roles');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181017_134444_ref_user_role_in_groups cannot be reverted.\n";

		return false;
	}
	*/
}
