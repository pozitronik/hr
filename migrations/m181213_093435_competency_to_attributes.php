<?php

use yii\db\Migration;

/**
 * Class m181213_093435_competency_to_attributes
 */
class m181213_093435_competency_to_attributes extends Migration {
	/**
	 * {@inheritdoc}
	 */

	public function safeUp() {
		$this->renameTable('sys_competencies', 'sys_attributes');
		$this->renameTable('sys_competencies_boolean', 'sys_attributes_boolean');
		$this->renameTable('sys_competencies_date', 'sys_attributes_date');
		$this->renameTable('sys_competencies_integer', 'sys_attributes_integer');
		$this->renameTable('sys_competencies_percent', 'sys_attributes_percent');
		$this->renameTable('sys_competencies_string', 'sys_attributes_string');
		$this->renameTable('sys_competencies_text', 'sys_attributes_text');
		$this->renameTable('sys_competencies_time', 'sys_attributes_time');

		$this->renameColumn('sys_attributes_boolean', 'competency_id', 'attribute_id');
		$this->renameColumn('sys_attributes_date', 'competency_id', 'attribute_id');
		$this->renameColumn('sys_attributes_integer', 'competency_id', 'attribute_id');
		$this->renameColumn('sys_attributes_percent', 'competency_id', 'attribute_id');
		$this->renameColumn('sys_attributes_string', 'competency_id', 'attribute_id');
		$this->renameColumn('sys_attributes_text', 'competency_id', 'attribute_id');
		$this->renameColumn('sys_attributes_time', 'competency_id', 'attribute_id');

		$this->dropIndex('competency_id', 'sys_attributes_boolean');
		$this->dropIndex('competency_id_field_id_user_id', 'sys_attributes_boolean');
		$this->createIndex('attribute_id', 'sys_attributes_boolean', 'attribute_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_boolean', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('competency_id', 'sys_attributes_date');
		$this->dropIndex('competency_id_field_id_user_id', 'sys_attributes_date');
		$this->createIndex('attribute_id', 'sys_attributes_date', 'attribute_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_date', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('competency_id', 'sys_attributes_integer');
		$this->dropIndex('competency_id_field_id_user_id', 'sys_attributes_integer');
		$this->createIndex('attribute_id', 'sys_attributes_integer', 'attribute_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_integer', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('competency_id', 'sys_attributes_percent');
		$this->dropIndex('competency_id_field_id_user_id', 'sys_attributes_percent');
		$this->createIndex('attribute_id', 'sys_attributes_percent', 'attribute_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_percent', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('competency_id', 'sys_attributes_string');
		$this->dropIndex('competency_id_field_id_user_id', 'sys_attributes_string');
		$this->createIndex('attribute_id', 'sys_attributes_string', 'attribute_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_string', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('competency_id', 'sys_attributes_text');
		$this->dropIndex('competency_id_field_id_user_id', 'sys_attributes_text');
		$this->createIndex('attribute_id', 'sys_attributes_text', 'attribute_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_text', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('competency_id', 'sys_attributes_time');
		$this->dropIndex('competency_id_field_id_user_id', 'sys_attributes_time');
		$this->createIndex('attribute_id', 'sys_attributes_time', 'attribute_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_time', ['attribute_id', 'field_id', 'user_id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameTable('sys_attributes', 'sys_competencies');
		$this->renameTable('sys_attributes_boolean', 'sys_competencies_boolean');
		$this->renameTable('sys_attributes_date', 'sys_competencies_date');
		$this->renameTable('sys_attributes_integer', 'sys_competencies_integer');
		$this->renameTable('sys_attributes_percent', 'sys_competencies_percent');
		$this->renameTable('sys_attributes_string', 'sys_competencies_string');
		$this->renameTable('sys_attributes_text', 'sys_competencies_text');
		$this->renameTable('sys_attributes_time', 'sys_competencies_time');

		$this->renameColumn('sys_competencies_boolean', 'attribute_id', 'competency_id');
		$this->renameColumn('sys_competencies_date', 'attribute_id', 'competency_id');
		$this->renameColumn('sys_competencies_integer', 'attribute_id', 'competency_id');
		$this->renameColumn('sys_competencies_percent', 'attribute_id', 'competency_id');
		$this->renameColumn('sys_competencies_string', 'attribute_id', 'competency_id');
		$this->renameColumn('sys_competencies_text', 'attribute_id', 'competency_id');
		$this->renameColumn('sys_competencies_time', 'attribute_id', 'competency_id');

		$this->dropIndex('attribute_id', 'sys_competencies_boolean');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_competencies_boolean');
		$this->createIndex('competency_id', 'sys_competencies_boolean', 'competency_id');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_boolean', ['competency_id', 'field_id', 'user_id']);

		$this->dropIndex('attribute_id', 'sys_competencies_date');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_competencies_date');
		$this->createIndex('competency_id', 'sys_competencies_date', 'competency_id');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_date', ['competency_id', 'field_id', 'user_id']);

		$this->dropIndex('attribute_id', 'sys_competencies_integer');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_competencies_integer');
		$this->createIndex('competency_id', 'sys_competencies_integer', 'competency_id');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_integer', ['competency_id', 'field_id', 'user_id']);

		$this->dropIndex('attribute_id', 'sys_competencies_percent');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_competencies_percent');
		$this->createIndex('competency_id', 'sys_competencies_percent', 'competency_id');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_percent', ['competency_id', 'field_id', 'user_id']);

		$this->dropIndex('attribute_id', 'sys_competencies_string');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_competencies_string');
		$this->createIndex('competency_id', 'sys_competencies_string', 'competency_id');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_string', ['competency_id', 'field_id', 'user_id']);

		$this->dropIndex('attribute_id', 'sys_competencies_text');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_competencies_text');
		$this->createIndex('competency_id', 'sys_competencies_text', 'competency_id');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_text', ['competency_id', 'field_id', 'user_id']);

		$this->dropIndex('attribute_id', 'sys_competencies_time');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_competencies_time');
		$this->createIndex('competency_id', 'sys_competencies_time', 'competency_id');
		$this->createIndex('competency_id_field_id_user_id', 'sys_competencies_time', ['competency_id', 'field_id', 'user_id']);
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181213_093435_competency_to_attributes cannot be reverted.\n";

		return false;
	}
	*/
}
