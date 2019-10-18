<?php

use yii\db\Migration;

/**
 * Class m191018_090638_user_roles_display_flag
 */
class m191018_090638_user_roles_display_flag extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('ref_user_roles','importance_flag',$this->boolean()->defaultValue(false)->notNull()->comment('Дополнительный флаг важности роли'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('ref_user_roles','importance_flag');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m191018_090638_user_roles_display_flag cannot be reverted.\n";

		return false;
	}
	*/
}
