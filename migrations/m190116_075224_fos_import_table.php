<?php

use yii\db\Migration;

/**
 * Class m190116_075224_fos_import_table
 */
class m190116_075224_fos_import_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_fos', [
			'id' => $this->primaryKey(),
			'num' => $this->string()->comment('№ п/п'),
			'position_id' => $this->string()->comment('ШД ID'),
			'position_name' => $this->string()->comment('Должность'),
			'user_id' => $this->string()->comment('ТН'),
			'user_name' => $this->string()->comment('Ф.И.О. сотрудника'),
			'functional_block' => $this->string()->comment('Функциональный блок'),
			'division_level_1' => $this->string()->comment('Подразделение 1 уровня'),
			'division_level_2' => $this->string()->comment('Подразделение 2 уровня'),
			'division_level_3' => $this->string()->comment('Подразделение 3 уровня'),
			'division_level_4' => $this->string()->comment('Подразделение 4 уровня'),
			'division_level_5' => $this->string()->comment('Подразделение 5 уровня'),
			'remote_flag' => $this->string()->comment('Признак УРМ'),
			'town' => $this->string()->comment('Населенный пункт'),
			'functional_block_tribe' => $this->string()->comment('Функциональный блок трайба'),
			'tribe_id' => $this->string()->comment('Трайб ID'),
			'tribe_code' => $this->string()->comment('Код трайба'),
			'tribe_name' => $this->string()->comment('Трайб'),
			'tribe_leader_id' => $this->string()->comment('Лидер трайба ТН'),
			'tribe_leader_name' => $this->string()->comment('Лидер трайба'),
			'tribe_leader_it_id' => $this->string()->comment('IT-лидер трайба ТН'),
			'tribe_leader_it_name' => $this->string()->comment('IT-лидер трайба'),
			'cluster_product_id' => $this->string()->comment('Кластер/Продукт ID'),
			'cluster_product_code' => $this->string()->comment('Код кластера/продукта'),
			'cluster_product_name' => $this->string()->comment('Кластер/Продукт'),
			'cluster_product_leader_id' => $this->string()->comment('Лидер кластера/продукта ТН'),
			'cluster_product_leader_name' => $this->string()->comment('Лидер кластера/продукта'),
			'command_id' => $this->string()->comment('Команда ID'),
			'command_code' => $this->string()->comment('Код команды'),
			'command_name' => $this->string()->comment('Команда'),
			'command_type' => $this->string()->comment('Тип команды'),
			'owner_name' => $this->string()->comment('Владелец продукта'),
			'command_position_id' => $this->string()->comment('Позиция в команде ID'),
			'command_position_code' => $this->string()->comment('Код позиции в команде'),
			'command_position_name' => $this->string()->comment('Позиция в команде'),
			'chapter_id' => $this->string()->comment('Чаптер ID'),
			'chapter_code' => $this->string()->comment('Код чаптера'),
			'chapter_name' => $this->string()->comment('Чаптер'),
			'chapter_leader_id' => $this->string()->comment('Лидер чаптера ТН'),
			'chapter_leader_name' => $this->string()->comment('Лидер чаптера'),
			'chapter_leader_couch_id' => $this->string()->comment('Agile-коуч ТН'),
			'chapter_leader_couch_name' => $this->string()->comment('Agile-коуч'),
			'email_sigma' => $this->string()->comment('Адрес электронной почты (sigma)'),
			'email_alpha' => $this->string()->comment('Адрес электронной почты (внутренний'),
			'domain' => $this->integer()->comment('Служеная метка очереди импорта')
		]);

		$this->createIndex('position_id', 'import_fos', 'position_id');
		$this->createIndex('user_id', 'import_fos', 'user_id');
		$this->createIndex('tribe_id', 'import_fos', 'tribe_id');
		$this->createIndex('tribe_leader_id', 'import_fos', 'tribe_leader_id');
		$this->createIndex('tribe_leader_it_id', 'import_fos', 'tribe_leader_it_id');
		$this->createIndex('cluster_product_id', 'import_fos', 'cluster_product_id');
		$this->createIndex('cluster_product_leader_id', 'import_fos', 'cluster_product_leader_id');
		$this->createIndex('command_id', 'import_fos', 'command_id');
		$this->createIndex('command_position_id', 'import_fos', 'command_position_id');
		$this->createIndex('chapter_id', 'import_fos', 'chapter_id');
		$this->createIndex('chapter_leader_id', 'import_fos', 'chapter_leader_id');
		$this->createIndex('chapter_leader_couch_id', 'import_fos', 'chapter_leader_couch_id');
		$this->createIndex('domain', 'import_fos', 'domain');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_fos');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190116_075224_fos_import_table cannot be reverted.\n";

		return false;
	}
	*/
}
