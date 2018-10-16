<?php

use yii\db\Migration;

/**
 * Class m181016_071941_ref_group_types
 */
class m181016_071941_ref_group_types extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_group_types', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull()->comment('Название'),
			'value' => $this->string(512)->notNull()->comment('Значение'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_group_types');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181016_071941_ref_group_types cannot be reverted.\n";

		return false;
	}
	*/
}
