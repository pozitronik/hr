<?php

use yii\db\Migration;

/**
 * Class m181116_082318_sys_competency
 */
class m181116_082318_sys_competency extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_competencies', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull()->comment('Название компетенции'),
			'category' => $this->integer()->null()->comment('Категория'),
			'daddy' => $this->integer()->null()->comment('Создатель'),
			'create_date' => $this->dateTime()->comment('Дата создания'),
			'structure' => $this->json()->notNull()->comment('Структура'),
			'access' => $this->integer()->notNull()->defaultValue(0)->comment('Доступ'),
			'deleted' => $this->boolean()->defaultValue(false)->notNull()->comment('Флаг удаления')
		]);
		$this->createIndex('name', 'sys_competencies', 'name');
		$this->createIndex('category', 'sys_competencies', 'category');
		$this->createIndex('access', 'sys_competencies', 'access');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_competencies');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181116_082318_sys_competency cannot be reverted.\n";

		return false;
	}
	*/
}
