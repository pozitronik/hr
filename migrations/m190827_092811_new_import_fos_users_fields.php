<?php

use yii\db\Migration;

/**
 * Class m190827_092811_new_import_fos_users_fields
 */
class m190827_092811_new_import_fos_users_fields extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('import_fos_users', 'birthday', $this->string());
		$this->addColumn('import_fos_users', 'expert_area', $this->string());
		$this->addColumn('import_fos_users', 'combined_role', $this->string());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('import_fos_users', 'birthday');
		$this->dropColumn('import_fos_users', 'expert_area');
		$this->dropColumn('import_fos_users', 'combined_role');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190827_092811_new_import_fos_users_fields cannot be reverted.\n";

		return false;
	}
	*/
}
