<?php

use yii\db\Migration;

/**
 * Class m181006_141340_workgroups
 */
class m181006_141340_workgroups extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('groups', [
			'id' => $this->primaryKey(),
			'name' => $this->string('512')->comment('Название'),
			'comment' => $this->text()->comment('Описание'),
			'deleted' => $this->boolean()->defaultValue(false)
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('groups');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181006_141340_workgroups cannot be reverted.\n";

		return false;
	}
	*/
}
