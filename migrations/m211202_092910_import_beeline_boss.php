<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m211202_092910_import_beeline_boss
 */
class m211202_092910_import_beeline_boss extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_beeline_boss', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255),
			'position' => $this->string(255),
			'level' => $this->integer()->null(),
			'hr_user_id' => $this->integer()->null(),
			'domain' => $this->integer(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_beeline_boss');
	}

}
