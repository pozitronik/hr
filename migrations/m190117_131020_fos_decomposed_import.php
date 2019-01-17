<?php

use yii\db\Migration;

/**
 * Class m190117_131020_fos_decomposed_import
 */
class m190117_131020_fos_decomposed_import extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_fos_decomposed', [
			'id' => $this->primaryKey(),
			'num' => $this->string()->comment('№ п/п'),
			'position_id' => $this->integer()->null(),
			'user_id' => $this->integer()->null(),
			'functional_block' => $this->integer()->null(),
			'division_level_1' => $this->integer()->null(),
			'division_level_2' => $this->integer()->null(),
			'division_level_3' => $this->integer()->null(),
			'division_level_4' => $this->integer()->null(),
			'division_level_5' => $this->integer()->null(),
			'functional_block_tribe' => $this->integer()->null(),
			'tribe_id' => $this->integer()->null(),
			'cluster_product_id' => $this->integer()->null(),
			'command_id' => $this->integer()->null(),
			'command_position_id' => $this->integer()->null(),
			'chapter_id' => $this->integer()->null(),
			'domain' => $this->integer()->comment('Служеная метка очереди импорта')
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_fos_decomposed');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190117_131020_fos_decomposed_import cannot be reverted.\n";

		return false;
	}
	*/
}
