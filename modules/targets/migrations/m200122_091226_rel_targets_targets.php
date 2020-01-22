<?php

use yii\db\Migration;

/**
 * Class m200122_091226_rel_targets_targets
 */
class m200122_091226_rel_targets_targets extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_targets_targets',[
			'id' => $this->primaryKey(),
			'parent_id' => $this->integer()->notNull()->comment('Вышестоящая цель'),
			'child_id' => $this->integer()->notNull()->comment('Нижестоящая цель'),
			'relation' => $this->integer()->null()->comment('Тип связи')
		]);

		$this->createIndex('parent_id', 'rel_targets_targets', 'parent_id');
		$this->createIndex('child_id', 'rel_targets_targets', 'child_id');
		$this->createIndex('parent_id_child_id', 'rel_targets_targets', ['parent_id', 'child_id']);
		$this->createIndex('parent_id_child_id_relation', 'rel_targets_targets', ['parent_id', 'child_id', 'relation'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_targets_targets');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200122_091226_rel_targets_targets cannot be reverted.\n";

		return false;
	}
	*/
}
