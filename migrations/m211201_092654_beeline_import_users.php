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
			'user_type' => $this->string(255)->null(),
			'affiliation' => $this->string(255)->null(),
			'position_profile_number' => $this->string(255)->null(),
			'is_boss' => $this->boolean()->defaultValue(false),
			'company_code' => $this->string(255)->null(),
			'cbo' => $this->string(255)->null(),
			'location' => $this->string(255)->null(),
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
