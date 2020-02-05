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
		$this->addColumn('ref_targets_types', 'parent', $this->integer()->null()->comment('id родительского типа цели, null если высший'));
		$this->createIndex('parent', 'ref_targets_types', 'parent');//Логично, что порядок уникален, но оставляем для удобства

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_targets_types', 'parent');
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
