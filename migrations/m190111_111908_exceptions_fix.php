<?php

use yii\db\Migration;

/**
 * Class m190111_111908_exceptions_fix
 */
class m190111_111908_exceptions_fix extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('sys_exceptions', 'get', $this->text()->null()->after('trace')->comment('GET'));
		$this->addColumn('sys_exceptions', 'post', $this->text()->null()->after('get')->comment('POST'));
		$this->addColumn('sys_exceptions', 'known', $this->boolean()->notNull()->defaultValue(false)->after('post')->comment('Known error'));

		$this->createIndex('known', 'sys_exceptions', 'known');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('sys_exceptions', 'known');
		$this->dropColumn('sys_exceptions', 'post');
		$this->dropColumn('sys_exceptions', 'get');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190111_111908_exceptions_fix cannot be reverted.\n";

		return false;
	}
	*/
}
