<?php

use yii\db\Migration;

/**
 * Class m190830_100402_fosimportuserpositiontype
 */
class m190830_100402_fosimportuserpositiontype extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('import_fos_users', 'position_type', $this->integer()->null()->comment('Тип должности (определяем по ФБ)'));;
		$this->createIndex('position_type', 'import_fos_users', 'position_type');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('import_fos_users', 'position_type');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190830_100402_fosimportuserpositiontype cannot be reverted.\n";

		return false;
	}
	*/
}
