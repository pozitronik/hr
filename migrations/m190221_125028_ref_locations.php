<?php

use yii\db\Migration;

/**
 * Class m190221_125028_ref_locations
 */
class m190221_125028_ref_locations extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_locations', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'color' => $this->string(255)->null(),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_locations');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190221_125028_ref_locations cannot be reverted.\n";

		return false;
	}
	*/
}
