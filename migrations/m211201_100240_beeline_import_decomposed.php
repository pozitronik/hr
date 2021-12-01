<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m211201_100240_beeline_import_decomposed
 */
class m211201_100240_beeline_import_decomposed extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_beeline_decomposed', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(),
			'business_block_id' => $this->integer(),
			'functional_block_id' => $this->integer(),
			'direction_id' => $this->integer(),
			'department_id' => $this->integer(),
			'service_id' => $this->integer(),
			'branch_id' => $this->integer(),
			'group_id' => $this->integer(),
			'level' => $this->integer(),
			'position_id' => $this->integer(),
			'domain' => $this->integer(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_beeline_decomposed');
	}

}
