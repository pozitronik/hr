<?php

use yii\db\Migration;

/**
 * Class m181119_132725_sys_competencies_all_types
 */
class m181119_132725_sys_competencies_all_types extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_competencies_boolean', [
			'id' => $this->primaryKey(),
			'competency_id' => $this->integer()->notNull()->comment('ID компетенции'),
			'field_id' => $this->integer()->notNull()->comment('ID поля'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value' => $this->boolean()->null()->comment('Значение')
		]);

		$this->createIndex('competency_id', 'sys_competencies_boolean', 'competency_id');
		$this->createIndex('field_id', 'sys_competencies_boolean', 'field_id');
		$this->createIndex('user_id', 'sys_competencies_boolean', 'user_id');
		$this->createIndex('value', 'sys_competencies_boolean', 'value');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_boolean', ['competency_id', 'field_id', 'user_id'], true);

		$this->createTable('sys_competencies_string', [
			'id' => $this->primaryKey(),
			'competency_id' => $this->integer()->notNull()->comment('ID компетенции'),
			'field_id' => $this->integer()->notNull()->comment('ID поля'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value' => $this->text()->null()->comment('Значение')
		]);

		$this->createIndex('competency_id', 'sys_competencies_string', 'competency_id');
		$this->createIndex('field_id', 'sys_competencies_string', 'field_id');
		$this->createIndex('user_id', 'sys_competencies_string', 'user_id');
//		$this->createIndex('value', 'sys_competencies_string', 'value');//Полнотекстового индекса тут нет
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_string', ['competency_id', 'field_id', 'user_id'], true);

		$this->createTable('sys_competencies_date', [
			'id' => $this->primaryKey(),
			'competency_id' => $this->integer()->notNull()->comment('ID компетенции'),
			'field_id' => $this->integer()->notNull()->comment('ID поля'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value' => $this->date()->null()->comment('Значение')
		]);

		$this->createIndex('competency_id', 'sys_competencies_date', 'competency_id');
		$this->createIndex('field_id', 'sys_competencies_date', 'field_id');
		$this->createIndex('user_id', 'sys_competencies_date', 'user_id');
		$this->createIndex('value', 'sys_competencies_date', 'value');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_date', ['competency_id', 'field_id', 'user_id'], true);

		$this->createTable('sys_competencies_time', [
			'id' => $this->primaryKey(),
			'competency_id' => $this->integer()->notNull()->comment('ID компетенции'),
			'field_id' => $this->integer()->notNull()->comment('ID поля'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value' => $this->time()->null()->comment('Значение')
		]);

		$this->createIndex('competency_id', 'sys_competencies_time', 'competency_id');
		$this->createIndex('field_id', 'sys_competencies_time', 'field_id');
		$this->createIndex('user_id', 'sys_competencies_time', 'user_id');
		$this->createIndex('value', 'sys_competencies_time', 'value');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_time', ['competency_id', 'field_id', 'user_id'], true);

		$this->createTable('sys_competencies_range', [
			'id' => $this->primaryKey(),
			'competency_id' => $this->integer()->notNull()->comment('ID компетенции'),
			'field_id' => $this->integer()->notNull()->comment('ID поля'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value_min' => $this->integer()->null()->comment('Нижний порог значения'),
			'value_max' => $this->integer()->null()->comment('Верхний порог значения')
		]);

		$this->createIndex('competency_id', 'sys_competencies_range', 'competency_id');
		$this->createIndex('field_id', 'sys_competencies_range', 'field_id');
		$this->createIndex('user_id', 'sys_competencies_range', 'user_id');
		$this->createIndex('value_min', 'sys_competencies_range', 'value_min');
		$this->createIndex('value_max', 'sys_competencies_range', 'value_max');
		$this->createIndex('value_min_value_max', 'sys_competencies_range', ['value_min', 'value_max']);
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_range', ['competency_id', 'field_id', 'user_id'], true);

		$this->createTable('sys_competencies_percent', [
			'id' => $this->primaryKey(),
			'competency_id' => $this->integer()->notNull()->comment('ID компетенции'),
			'field_id' => $this->integer()->notNull()->comment('ID поля'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value' => $this->integer(100)->null()->comment('Значение')
		]);

		$this->createIndex('competency_id', 'sys_competencies_percent', 'competency_id');
		$this->createIndex('field_id', 'sys_competencies_percent', 'field_id');
		$this->createIndex('user_id', 'sys_competencies_percent', 'user_id');
		$this->createIndex('value', 'sys_competencies_percent', 'value');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_percent', ['competency_id', 'field_id', 'user_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_competencies_boolean');
		$this->dropTable('sys_competencies_string');
		$this->dropTable('sys_competencies_date');
		$this->dropTable('sys_competencies_time');
		$this->dropTable('sys_competencies_range');
		$this->dropTable('sys_competencies_percent');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181119_132725_sys_competencies_all_types cannot be reverted.\n";

		return false;
	}
	*/
}
