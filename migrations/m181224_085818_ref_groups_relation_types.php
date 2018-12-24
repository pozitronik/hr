<?php

use yii\db\Migration;

/**
 * Class m181224_085818_ref_groups_relation_types
 */
class m181224_085818_ref_groups_relation_types extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_group_relation_types', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull()->comment('Название'),
			'value' => $this->string(512)->notNull()->comment('Описание'),
			'color' => $this->string(255)->null()->comment('Цветокод'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_group_relation_types');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181224_085818_ref_groups_relation_types cannot be reverted.\n";

		return false;
	}
	*/
}
