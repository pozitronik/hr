<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Class m211125_121709_beeline_import_structure
 */
class m211125_121709_beeline_import_structure extends Migration {

	private const TABLES = [
		'import_beeline_business_block',
		'import_beeline_functional_block',
		'import_beeline_direction',
		'import_beeline_department',
		'import_beeline_service',
		'import_beeline_branch',
		'import_beeline_group'
	];

	/**
	 * @return array
	 */
	public function groupFieldset():array {
		return [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->unique(),
			'domain' => $this->integer(),
			'hr_group_id' => $this->integer()
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		foreach (self::TABLES as $table) {
			$this->createTable($table, $this->groupFieldset());
			$this->createIndex('domain', $table, 'domain');
		}

		$this->createTable('import_beeline', [
			'id' => $this->primaryKey(),
			'business_block' => $this->string(255),
			'functional_block' => $this->string(255),
			'direction' => $this->string(255),
			'department' => $this->string(255),
			'service' => $this->string(255),
			'branch' => $this->string(255),
			'group' => $this->string(255),
			'ceo_level' => $this->string(255),
			'user_type' => $this->string(255),
			'position_name' => $this->string(255),
			'user_tn' => $this->string(255),
			'user_name' => $this->string(255),
			'administrative_boss_name' => $this->string(255),
			'administrative_boss_position_name' => $this->string(255),
			'functional_boss_name' => $this->string(255),
			'functional_boss_position_name' => $this->string(255),
			'affiliation' => $this->string(255),
			'position_profile_number' => $this->string(255),
			'is_boss' => $this->string(255),
			'company_code' => $this->string(255),
			'cbo' => $this->string(255),
			'location' => $this->string(255),
			'commentary' => $this->string(255),
			'domain' => $this->integer()
		]);

		$this->createIndex('domain', 'import_beeline', 'domain');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		foreach (self::TABLES as $table) {
			$this->dropTable($table);
		}
		$this->dropTable('import_beeline');
	}

}
