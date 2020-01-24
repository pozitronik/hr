<?php

use yii\db\Migration;

/**
 * Class m200124_091856_targets_types_colrs
 */
class m200124_091856_targets_types_colrs extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ref_targets_types', 'color', $this->string(255)->null()->comment('Цветокод'));
		$this->addColumn('ref_targets_types', 'textcolor', $this->string(255)->null()->comment('Цвет шрифта'));
		$this->addColumn('ref_targets_results', 'color', $this->string(255)->null()->comment('Цветокод'));
		$this->addColumn('ref_targets_results', 'textcolor', $this->string(255)->null()->comment('Цвет шрифта'));

		$this->createIndex('color', 'ref_targets_types', 'color');
		$this->createIndex('textcolor', 'ref_targets_types', 'textcolor');
		$this->createIndex('color', 'ref_targets_results', 'color');
		$this->createIndex('textcolor', 'ref_targets_results', 'textcolor');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_targets_types', 'color');
		$this->dropColumn('ref_targets_types', 'textcolor');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200124_091856_targets_types_colrs cannot be reverted.\n";

		return false;
	}
	*/
}
