<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m211201_092654_beeline_import_users
 */
class m211201_092654_beeline_import_users extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('import_beeline_users', [
			'id' => $this->primaryKey(),
			'user_tn' => $this->integer()->null(),
			'name' => $this->string(255),
			'position' => $this->string(255),
			'level' => $this->integer(),
			'domain' => $this->integer(),
			'hr_user_id' => $this->integer()->null()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('import_beeline_users');
	}
}
