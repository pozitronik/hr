<?php

use yii\db\Migration;

/**
 * Class m181213_124842_fields_to_attributes
 */
class m181213_124842_fields_to_attributes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropIndex('field_id', 'sys_attributes_boolean');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_attributes_boolean');
		$this->renameColumn('sys_attributes_boolean', 'field_id', 'property_id');
		$this->createIndex('property_id', 'sys_attributes_boolean', 'property_id');
		$this->createIndex('attribute_id_property_id_user_id', 'sys_attributes_boolean', ['attribute_id', 'property_id', 'user_id']);

		$this->dropIndex('field_id', 'sys_attributes_date');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_attributes_date');
		$this->renameColumn('sys_attributes_date', 'field_id', 'property_id');
		$this->createIndex('property_id', 'sys_attributes_date', 'property_id');
		$this->createIndex('attribute_id_property_id_user_id', 'sys_attributes_date', ['attribute_id', 'property_id', 'user_id']);

		$this->dropIndex('field_id', 'sys_attributes_integer');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_attributes_integer');
		$this->renameColumn('sys_attributes_integer', 'field_id', 'property_id');
		$this->createIndex('property_id', 'sys_attributes_integer', 'property_id');
		$this->createIndex('attribute_id_property_id_user_id', 'sys_attributes_integer', ['attribute_id', 'property_id', 'user_id']);

		$this->dropIndex('field_id', 'sys_attributes_percent');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_attributes_percent');
		$this->renameColumn('sys_attributes_percent', 'field_id', 'property_id');
		$this->createIndex('property_id', 'sys_attributes_percent', 'property_id');
		$this->createIndex('attribute_id_property_id_user_id', 'sys_attributes_percent', ['attribute_id', 'property_id', 'user_id']);

		$this->dropIndex('field_id', 'sys_attributes_string');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_attributes_string');
		$this->renameColumn('sys_attributes_string', 'field_id', 'property_id');
		$this->createIndex('property_id', 'sys_attributes_string', 'property_id');
		$this->createIndex('attribute_id_property_id_user_id', 'sys_attributes_string', ['attribute_id', 'property_id', 'user_id']);

		$this->dropIndex('field_id', 'sys_attributes_text');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_attributes_text');
		$this->renameColumn('sys_attributes_text', 'field_id', 'property_id');
		$this->createIndex('property_id', 'sys_attributes_text', 'property_id');
		$this->createIndex('attribute_id_property_id_user_id', 'sys_attributes_text', ['attribute_id', 'property_id', 'user_id']);

		$this->dropIndex('field_id', 'sys_attributes_time');
		$this->dropIndex('attribute_id_field_id_user_id', 'sys_attributes_time');
		$this->renameColumn('sys_attributes_time', 'field_id', 'property_id');
		$this->createIndex('property_id', 'sys_attributes_time', 'property_id');
		$this->createIndex('attribute_id_property_id_user_id', 'sys_attributes_time', ['attribute_id', 'property_id', 'user_id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('property_id', 'sys_attributes_boolean');
		$this->dropIndex('attribute_id_property_id_user_id', 'sys_attributes_boolean');
		$this->renameColumn('sys_attributes_boolean', 'property_id', 'field_id');
		$this->createIndex('field_id', 'sys_attributes_boolean', 'field_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_boolean', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('property_id', 'sys_attributes_date');
		$this->dropIndex('attribute_id_property_id_user_id', 'sys_attributes_date');
		$this->renameColumn('sys_attributes_date', 'property_id', 'field_id');
		$this->createIndex('field_id', 'sys_attributes_date', 'field_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_date', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('property_id', 'sys_attributes_integer');
		$this->dropIndex('attribute_id_property_id_user_id', 'sys_attributes_integer');
		$this->renameColumn('sys_attributes_integer', 'property_id', 'field_id');
		$this->createIndex('field_id', 'sys_attributes_integer', 'field_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_integer', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('property_id', 'sys_attributes_percent');
		$this->dropIndex('attribute_id_property_id_user_id', 'sys_attributes_percent');
		$this->renameColumn('sys_attributes_percent', 'property_id', 'field_id');
		$this->createIndex('field_id', 'sys_attributes_percent', 'field_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_percent', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('property_id', 'sys_attributes_string');
		$this->dropIndex('attribute_id_property_id_user_id', 'sys_attributes_string');
		$this->renameColumn('sys_attributes_string', 'property_id', 'field_id');
		$this->createIndex('field_id', 'sys_attributes_string', 'field_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_string', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('property_id', 'sys_attributes_text');
		$this->dropIndex('attribute_id_property_id_user_id', 'sys_attributes_text');
		$this->renameColumn('sys_attributes_text', 'property_id', 'field_id');
		$this->createIndex('field_id', 'sys_attributes_text', 'field_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_text', ['attribute_id', 'field_id', 'user_id']);

		$this->dropIndex('property_id', 'sys_attributes_time');
		$this->dropIndex('attribute_id_property_id_user_id', 'sys_attributes_time');
		$this->renameColumn('sys_attributes_time', 'property_id', 'field_id');
		$this->createIndex('field_id', 'sys_attributes_time', 'field_id');
		$this->createIndex('attribute_id_field_id_user_id', 'sys_attributes_time', ['attribute_id', 'field_id', 'user_id']);
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181213_124842_fields_to_attributes cannot be reverted.\n";

		return false;
	}
	*/
}
