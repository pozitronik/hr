<?php

use yii\db\Migration;

/**
 * Class m190816_064916_vacancy_index
 */
class m190816_064916_vacancy_index extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createIndex('status', 'sys_vacancy', 'status');
		$this->createIndex('vacancy_id', 'sys_vacancy', 'vacancy_id');
		$this->createIndex('ticket_id', 'sys_vacancy', 'ticket_id');
		$this->createIndex('group', 'sys_vacancy', 'group');
		$this->createIndex('name', 'sys_vacancy', 'name');
		$this->createIndex('location', 'sys_vacancy', 'location');
		$this->createIndex('recruiter', 'sys_vacancy', 'recruiter');
		$this->createIndex('employer', 'sys_vacancy', 'employer');
		$this->createIndex('position', 'sys_vacancy', 'position');
		$this->createIndex('premium_group', 'sys_vacancy', 'premium_group');
		$this->createIndex('grade', 'sys_vacancy', 'grade');
		$this->createIndex('teamlead', 'sys_vacancy', 'teamlead');
		$this->createIndex('username', 'sys_vacancy', 'username');
		$this->createIndex('create_date', 'sys_vacancy', 'create_date');
		$this->createIndex('close_date', 'sys_vacancy', 'close_date');
		$this->createIndex('estimated_close_date', 'sys_vacancy', 'estimated_close_date');
		$this->createIndex('daddy', 'sys_vacancy', 'daddy');
		$this->createIndex('deleted', 'sys_vacancy', 'deleted');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('status', 'sys_vacancy');
		$this->dropIndex('vacancy_id', 'sys_vacancy');
		$this->dropIndex('ticket_id', 'sys_vacancy');
		$this->dropIndex('group', 'sys_vacancy');
		$this->dropIndex('name', 'sys_vacancy');
		$this->dropIndex('location', 'sys_vacancy');
		$this->dropIndex('recruiter', 'sys_vacancy');
		$this->dropIndex('employer', 'sys_vacancy');
		$this->dropIndex('position', 'sys_vacancy');
		$this->dropIndex('premium_group', 'sys_vacancy');
		$this->dropIndex('grade', 'sys_vacancy');
		$this->dropIndex('teamlead', 'sys_vacancy');
		$this->dropIndex('username', 'sys_vacancy');
		$this->dropIndex('create_date', 'sys_vacancy');
		$this->dropIndex('close_date', 'sys_vacancy');
		$this->dropIndex('estimated_close_date', 'sys_vacancy');
		$this->dropIndex('daddy', 'sys_vacancy');
		$this->dropIndex('deleted', 'sys_vacancy');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190816_064916_vacancy_index cannot be reverted.\n";

		return false;
	}
	*/
}
