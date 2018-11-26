<?php

use yii\db\Migration;

/**
 * Class m181126_074232_competence_text
 */
class m181126_074232_competence_text extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameTable('sys_competencies_string', 'sys_competencies_text');

		$this->createTable('sys_competencies_string', [
			'id' => $this->primaryKey(),
			'competency_id' => $this->integer()->notNull()->comment('ID компетенции'),
			'field_id' => $this->integer()->notNull()->comment('ID поля'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value' => $this->string(255)->null()->comment('Значение')
		]);

		$this->createIndex('competency_id', 'sys_competencies_string', 'competency_id');
		$this->createIndex('field_id', 'sys_competencies_string', 'field_id');
		$this->createIndex('user_id', 'sys_competencies_string', 'user_id');
		$this->createIndex('value', 'sys_competencies_string', 'value');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_string', ['competency_id', 'field_id', 'user_id'], true);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_competencies_string');

		$this->renameTable('sys_competencies_text', 'sys_competencies_string');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181126_074232_competence_text cannot be reverted.\n";

		return false;
	}
	*/
}
