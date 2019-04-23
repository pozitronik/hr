<?php

use yii\db\Migration;

/**
 * Class m190423_095024_alter_vacancy
 */
class m190423_095024_alter_vacancy extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('sys_vacancy','status',$this->integer()->null()->defaultValue(0)->comment('Статус'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('sys_vacancy','status',$this->integer()->notNull()->defaultValue(0)->comment('Статус'));
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190423_095024_alter_vacancy cannot be reverted.\n";

		return false;
	}
	*/
}
