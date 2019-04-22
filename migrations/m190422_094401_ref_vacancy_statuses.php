<?php

use yii\db\Migration;

/**
 * Class m190422_094401_ref_vacancy_statuses
 */
class m190422_094401_ref_vacancy_statuses extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_vacancy_statuses',[
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'color' => $this->string(255)->null(),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_vacancy_statuses');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190422_094401_ref_vacancy_statuses cannot be reverted.\n";

		return false;
	}
	*/
}
