<?php

use yii\db\Migration;

/**
 * Class m190205_150738_ref_atributes_types
 */
class m190205_150738_ref_atributes_types extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_attributes_types', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'color' => $this->string(255)->null(),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

		$this->createTable('rel_users_attributes_types', [
			'user_attribute_id' => $this->integer()->notNull(),
			'type' => $this->integer()->notNull()
		]);

		$this->createIndex('user_attribute_id_type', 'rel_users_attributes_types', ['user_attribute_id', 'type'], true);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_users_attributes_types');
		$this->dropTable('ref_attributes_types');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190205_150738_ref_atributes_types cannot be reverted.\n";

		return false;
	}
	*/
}
