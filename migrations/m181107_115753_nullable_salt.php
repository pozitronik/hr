<?php

use yii\db\Migration;

/**
 * Class m181107_115753_nullable_salt
 */
class m181107_115753_nullable_salt extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('sys_users', 'salt', $this->string(255)->null()->comment('Unique random salt hash'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('sys_users', 'salt', $this->string(255)->notNull()->comment('Unique random salt hash'));
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181107_115753_nullable_salt cannot be reverted.\n";

		return false;
	}
	*/
}
