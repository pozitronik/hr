<?php

use yii\db\Migration;

/**
 * Class m190128_094203_alter_score
 */
class m190128_094203_alter_score extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('sys_attributes_score', 'value');

		$this->addColumn('sys_attributes_score', 'self_score_value', $this->integer()->null()->comment('Оценка сотрудника (СО)'));
		$this->addColumn('sys_attributes_score', 'self_score_comment', $this->string()->null()->comment('Комментарий к самооценке'));

		$this->addColumn('sys_attributes_score', 'tl_score_value', $this->integer()->null()->comment('Оценка тимлида (TL)'));
		$this->addColumn('sys_attributes_score', 'tl_score_comment', $this->string()->null()->comment('Комментарий к оценке тимлида'));

		$this->addColumn('sys_attributes_score', 'al_score_value', $this->integer()->null()->comment('Оценка ареалида (AL)'));
		$this->addColumn('sys_attributes_score', 'al_score_comment', $this->string()->null()->comment('Комментарий к оценке ареалида'));

		$this->createIndex('self_score_value', 'sys_attributes_score', 'self_score_value');
		$this->createIndex('self_score_comment', 'sys_attributes_score', 'self_score_comment');
		$this->createIndex('tl_score_value', 'sys_attributes_score', 'tl_score_value');
		$this->createIndex('tl_score_comment', 'sys_attributes_score', 'tl_score_comment');
		$this->createIndex('al_score_value', 'sys_attributes_score', 'al_score_value');
		$this->createIndex('al_score_comment', 'sys_attributes_score', 'al_score_comment');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_attributes_score', 'self_score_value');
		$this->dropColumn('sys_attributes_score', 'self_score_comment');
		$this->dropColumn('sys_attributes_score', 'tl_score_value');
		$this->dropColumn('sys_attributes_score', 'tl_score_comment');
		$this->dropColumn('sys_attributes_score', 'al_score_value');
		$this->dropColumn('sys_attributes_score', 'al_score_comment');
		$this->addColumn('sys_attributes_score', 'value', $this->integer()->null());
		$this->createIndex('value', 'sys_attributes_score', 'value');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190128_094203_alter_score cannot be reverted.\n";

		return false;
	}
	*/
}
