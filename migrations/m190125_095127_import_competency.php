<?php

use yii\db\Migration;

/**
 * Class m190125_095127_import_competency
 */
class m190125_095127_import_competency extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_competency_users', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull()->comment('Имя сотрудника'),
			'hr_user_id' => $this->integer()->null()->comment('id в системе')
		]);

		$this->createTable('import_competency_attributes', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull()->comment('Название атрибута'),
			'hr_attribute_id' => $this->integer()->comment('id в системе')
		]);

		$this->createTable('import_competency_fields', [
			'id' => $this->primaryKey(),
			'attribute_id' => $this->integer()->notNull()->comment('Ключ к атрибуту'),
			'name' => $this->string()->notNull()
		]);

		$this->createTable('import_competency_rel_users_fields', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull()->comment('Ключ к пользователю'),
			'field_id' => $this->integer()->notNull()->comment('Ключ к полю атрибута'),
			'value' => $this->text()->comment('Значение поля в сыром виде')
		]);

		$this->createIndex('attribute_id', 'import_competency_fields', 'attribute_id');
		$this->createIndex('user_id', 'import_competency_rel_users_fields', 'user_id');
		$this->createIndex('field_id', 'import_competency_rel_users_fields', 'field_id');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_competency_users');
		$this->dropTable('import_competency_attributes');
		$this->dropTable('import_competency_fields');
		$this->dropTable('import_competency_rel_users_fields');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190125_095127_import_competency cannot be reverted.\n";

		return false;
	}
	*/
}
