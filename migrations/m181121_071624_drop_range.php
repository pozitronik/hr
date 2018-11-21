<?php

use yii\db\Migration;

/**
 * Class m181121_071624_drop_range
 */
class m181121_071624_drop_range extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropTable('sys_competencies_range');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
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
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181121_071624_drop_range cannot be reverted.\n";

		return false;
	}
	*/
}
