<?php

use yii\db\Migration;

/**
 * Class m200122_091127_targets_results
 */
class m200122_091127_targets_results extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {//Шкала
		$this->createTable('sys_targets_results',[
			'id' => $this->primaryKey(),
			'target' => $this->integer()->notNull()->comment('id цели'),
			'comment' => $this->text()->null()->comment('Описание результата'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата создания'),
			'marked_date' => $this->dateTime()->notNull()->comment('Дата, на которую приходится результата'),
			'daddy' => $this->integer()->null()->comment('ID зарегистрировавшего пользователя'),
			'status' => $this->integer()->null()->comment('Тип статуса исполнения'),
		]);

		$this->createIndex('target','sys_targets_results','target');
		$this->createIndex('daddy','sys_targets_results','daddy');
		$this->createIndex('status','sys_targets_results','status');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_targets_results');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200122_091127_targets_results cannot be reverted.\n";

		return false;
	}
	*/
}
