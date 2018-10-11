<?php

use yii\db\Migration;

/**
 * Class m181011_081655_rel_groups_groups
 */
class m181011_081655_rel_groups_groups extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('rel_groups_groups',[
			'id' => $this->primaryKey(),
			'parent_id' => $this->integer()->notNull()->comment('Вышестоящая группа'),
			'child_id' => $this->integer()->notNull()->comment('Нижестоящая группа'),
			'relation' => $this->integer()->null()->comment('Тип связи')
		]);

		$this->createIndex('parent_id', 'rel_groups_groups', 'parent_id');
		$this->createIndex('child_id', 'rel_groups_groups', 'child_id');
		$this->createIndex('parent_id_child_id', 'rel_groups_groups', ['parent_id', 'child_id']);
		$this->createIndex('parent_id_child_id_relation', 'rel_groups_groups', ['parent_id', 'child_id', 'relation'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('rel_groups_groups');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181011_081655_rel_groups_groups cannot be reverted.\n";

		return false;
	}
	*/
}
