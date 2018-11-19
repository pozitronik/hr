<?php

use yii\db\Migration;

/**
 * Class m181119_084451_sys_competencies_integer
 */
class m181119_084451_sys_competencies_integer extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_competencies_integer', [
			'id' => $this->primaryKey(),
			'competency_id' => $this->integer()->notNull()->comment('ID компетенции'),
			'field_id' => $this->integer()->notNull()->comment('ID поля'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value' => $this->integer()->null()->comment('Значение')
		]);

		$this->createIndex('competency_id', 'sys_competencies_integer', 'competency_id');
		$this->createIndex('field_id', 'sys_competencies_integer', 'field_id');
		$this->createIndex('user_id', 'sys_competencies_integer', 'user_id');
		$this->createIndex('value', 'sys_competencies_integer', 'value');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_integer', ['competency_id', 'field_id', 'user_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_competencies_integer');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181119_084451_sys_competencies_integer cannot be reverted.\n";

		return false;
	}
	*/
}
