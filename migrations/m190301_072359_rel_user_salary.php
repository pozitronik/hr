<?php

use yii\db\Migration;

/**
 * Class m190301_072359_rel_user_salary
 */
class m190301_072359_rel_user_salary extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_users_salary', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'grade_id' => $this->integer()->null(),
			'premium_group_id' => $this->integer()->null(),
			'location_id' => $this->integer()->null()
		]);

		$this->createIndex('user_id', 'rel_users_salary', 'user_id', true);
		$this->createIndex('grade_id', 'rel_users_salary', 'grade_id');
		$this->createIndex('premium_group_id', 'rel_users_salary', 'premium_group_id');
		$this->createIndex('location_id', 'rel_users_salary', 'location_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_users_salary');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190301_072359_rel_user_salary cannot be reverted.\n";

		return false;
	}
	*/
}
