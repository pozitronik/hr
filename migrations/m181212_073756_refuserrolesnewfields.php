<?php

use yii\db\Migration;

/**
 * Class m181212_073756_refuserrolesnewfields
 */
class m181212_073756_refuserrolesnewfields extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ref_user_roles', 'boss_flag', $this->boolean()->notNull()->defaultValue(false)->comment('Флаг лидера группы'));
		$this->addColumn('ref_user_roles', 'color', $this->integer()->null()->comment('Цветокод'));
		$this->createIndex('boss_flag', 'ref_user_roles', 'boss_flag');
		$this->createIndex('color', 'ref_user_roles', 'color');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_user_roles', 'boss_flag');
		$this->dropColumn('ref_user_roles', 'color');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m181212_073756_refuserrolesnewfields cannot be reverted.\n";

		return false;
	}
	*/
}
