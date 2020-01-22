<?php

use yii\db\Migration;

/**
 * Class m200122_082050_sys_targets
 */
class m200122_082050_sys_targets extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_targets', [
			'id' => $this->primaryKey(),
			'type' => $this->integer()->notNull()->comment('id типа цели'),
			'result_type' => $this->integer()->null()->comment('id типа результата'),
			'group_id' => $this->integer()->notNull(),
			'name' => $this->string(512)->notNull(),
			'comment' => $this->text()->null()->comment('Описание цели'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата создания'),
			'daddy' => $this->integer()->null()->comment('ID зарегистрировавшего/проверившего пользователя'),
			'deleted' => $this->boolean()->defaultValue(0)->comment('Флаг удаления')
		]);

		$this->createIndex('type','sys_targets','type');
		$this->createIndex('result_type','sys_targets','result_type');
		$this->createIndex('group_id','sys_targets','group_id');
		$this->createIndex('deleted','sys_targets','deleted');
		$this->createIndex('daddy','sys_targets','daddy');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_targets');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200122_082050_sys_targets cannot be reverted.\n";

		return false;
	}
	*/
}
