<?php

use yii\db\Migration;

/**
 * Class m200122_091043_ref_targets_types
 */
class m200122_091043_ref_targets_types extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_targets_types', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull()->comment('Название'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_targets_types');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200122_091043_ref_targets_types cannot be reverted.\n";

		return false;
	}
	*/
}
