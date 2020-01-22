<?php

use yii\db\Migration;

/**
 * Class m200122_091202_tragets_budgets
 */
class m200122_091202_tragets_budgets extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_targets_budgets', [
			'id' => $this->primaryKey(),
			'target' => $this->integer()->notNull()->comment('id цели'),
			'comment' => $this->text()->null()->comment('Описание бюджета'),
			'value' => $this->integer()->null()->comment('Значение бюджета в цифрах'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата создания'),
			'marked_date' => $this->dateTime()->notNull()->comment('Дата, на которую изменение активно'),
			'daddy' => $this->integer()->null()->comment('ID зарегистрировавшего пользователя'),
		]);

		$this->createIndex('value', 'sys_targets_budgets', 'value');
		$this->createIndex('target', 'sys_targets_budgets', 'target');
		$this->createIndex('daddy', 'sys_targets_budgets', 'daddy');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_targets_budgets');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200122_091202_tragets_budgets cannot be reverted.\n";

		return false;
	}
	*/
}
