<?php

use yii\db\Migration;

/**
 * Class m181006_190403_users_profile_image
 */
class m181006_190403_users_profile_image extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_users', 'profile_image', $this->string()->null()->comment('Фото профиля')->after('create_date'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_users', 'profile_image');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181006_190403_users_profile_image cannot be reverted.\n";

		return false;
	}
	*/
}
