<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m211209_181110_savkin_imports_structure_changes
 */
class m211209_181110_savkin_imports_structure_changes extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn('import_beeline', 'tribe', $this->string()->null()->after('commentary'));
		$this->addColumn('import_beeline', 'tribe_leader', $this->string()->null()->after('tribe'));
		$this->addColumn('import_beeline', 'command', $this->string()->null()->after('tribe_leader'));
		$this->addColumn('import_beeline', 'product_owner', $this->string()->null()->after('command'));

		$this->createTable('import_beeline_tribe_leader', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(),
			'domain' => $this->integer()->notNull()
		]);

		$this->createIndex('domain', 'import_beeline_tribe_leader', 'domain');

		$this->createTable('import_beeline_product_owner', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(),
			'domain' => $this->integer()->notNull()
		]);

		$this->createIndex('domain', 'import_beeline_product_owner', 'domain');

		$this->createTable('import_beeline_tribe', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->null(),
			'domain' => $this->integer()->notNull()
		]);

		$this->createIndex('domain', 'import_beeline_tribe', 'domain');

		$this->createTable('import_beeline_command', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->null(),
			'domain' => $this->integer()->notNull()
		]);

		$this->createIndex('domain', 'import_beeline_command', 'domain');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropColumn('import_beeline', 'tribe');
		$this->dropColumn('import_beeline', 'tribe_leader');
		$this->dropColumn('import_beeline', 'command');
		$this->dropColumn('import_beeline', 'product_owner');

		$this->dropTable('import_beeline_tribe_leader');
		$this->dropTable('import_beeline_product_owner');
		$this->dropTable('import_beeline_tribe');
		$this->dropTable('import_beeline_command');
	}

}
