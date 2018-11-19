<?php

use yii\db\Migration;

/**
 * Class m181119_092536_rel_users_competencies
 */
class m181119_092536_rel_users_competencies extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_users_competencies', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'competency_id' => $this->integer()->notNull()
		]);

		$this->createIndex('user_id', 'rel_users_competencies', 'user_id');
		$this->createIndex('competency_id', 'rel_users_competencies', 'competency_id');
		$this->createIndex('user_id_competency_id', 'rel_users_competencies', ['user_id', 'competency_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_users_competencies');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181119_092536_rel_users_competencies cannot be reverted.\n";

		return false;
	}
	*/
}
