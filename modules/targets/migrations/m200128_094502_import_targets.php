<?php

use yii\db\Migration;

/**
 * Class m200128_094502_import_targets
 */
class m200128_094502_import_targets extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_targets', [
			'id' => $this->primaryKey(),
			'clusterName' => $this->string()->null(),
			'commandName' => $this->string()->null(),
			'commandCode' => $this->string()->null(),
			'subInit' => $this->string()->null(),
			'milestone' => $this->string()->null(),
			'target' => $this->string()->null(),
			'targetResult' => $this->string()->null(),
			'resultValue' => $this->string()->null(),
			'period' => $this->string()->null(),
			'isYear' => $this->string()->null(),
			'isLK' => $this->string()->null(),
			'isLT' => $this->string()->null(),
			'curator' => $this->string()->null(),
			'comment' => $this->string()->null(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_targets');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200128_094502_import_targets cannot be reverted.\n";

		return false;
	}
	*/
}
