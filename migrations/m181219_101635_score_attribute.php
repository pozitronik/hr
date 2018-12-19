<?php

use yii\db\Migration;

/**
 * Class m181219_101635_score_attribute
 */
class m181219_101635_score_attribute extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('sys_attributes_score',[
			'id' => $this->primaryKey(),
			'attribute_id' => $this->integer()->notNull()->comment('ID атрибута'),
			'property_id' => $this->integer()->notNull()->comment('ID свойства'),
			'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
			'value' => $this->integer()->null()->comment('Значение')
		]);

		$this->createIndex('attribute_id', 'sys_attributes_score', 'attribute_id');
		$this->createIndex('property_id', 'sys_attributes_score', 'property_id');
		$this->createIndex('user_id', 'sys_attributes_score', 'user_id');
		$this->createIndex('value', 'sys_attributes_score', 'value');
		$this->createIndex('attribute_id_property_id_user_id', 'sys_attributes_score', ['attribute_id', 'property_id', 'user_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('sys_attributes_score');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181219_101635_score_attribute cannot be reverted.\n";

		return false;
	}
	*/
}
