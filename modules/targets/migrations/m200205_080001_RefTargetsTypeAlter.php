<?php

use yii\db\Migration;

/**
 * Class m200205_080001_RefTargetsTypeAlter
 */
class m200205_080001_RefTargetsTypeAlter extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ref_targets_types', 'order', $this->integer()->notNull()->defaultValue(-1)->comment('Порядок включения целей, чем меньше - тем больше цель'));
		$this->createIndex('order', 'ref_targets_types', 'order');//Логично, что порядок уникален, но оставляем для удобства

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_targets_types', 'order');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200205_080001_RefTargetsTypeAlter cannot be reverted.\n";

		return false;
	}
	*/
}
