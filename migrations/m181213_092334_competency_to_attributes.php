<?php

use yii\db\Migration;

/**
 * Class m181213_092334_competency_to_attributes
 */
class m181213_092334_competency_to_attributes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameTable('rel_users_competencies', 'rel_users_attributes');
		$this->renameColumn('rel_users_attributes', 'competency_id', 'attribute_id');
		$this->dropIndex('competency_id', 'rel_users_attributes');
		$this->dropIndex('user_id_competency_id', 'rel_users_attributes');
		$this->createIndex('attribute_id', 'rel_users_attributes', 'attribute_id');
		$this->createIndex('user_id_attribute_id', 'rel_users_attributes', ['user_id', 'attribute_id']);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameTable('rel_users_attributes', 'rel_users_competencies');
		$this->renameColumn('rel_users_competencies', 'attribute_id', 'competency_id');
		$this->dropIndex('attribute_id', 'rel_users_attributes');
		$this->dropIndex('user_id_attribute_id', 'rel_users_competencies');
		$this->createIndex('competency_id', 'rel_users_competencies', 'competency_id');
		$this->createIndex('user_id_competency_id', 'rel_users_competencies', ['user_id', 'competency_id']);
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181213_092334_competency_to_attributes cannot be reverted.\n";

		return false;
	}
	*/
}
