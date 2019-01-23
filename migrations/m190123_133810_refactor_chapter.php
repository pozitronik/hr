<?php

use yii\db\Migration;

/**
 * Class m190123_133810_refactor_chapter
 */
class m190123_133810_refactor_chapter extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameColumn('import_fos_chapter', 'id', 'chapter_id');
		$this->renameColumn('import_fos_chapter', 'pkey', 'id');
		$this->createIndex('chapter_id', 'import_fos_chapter', 'chapter_id', true);
		$this->createIndex('domain', 'import_fos_chapter', 'domain');
		$this->createIndex('leader_id', 'import_fos_chapter', 'leader_id');
		$this->createIndex('couch_id', 'import_fos_chapter', 'couch_id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropIndex('leader_id', 'import_fos_chapter');
		$this->dropIndex('couch_id', 'import_fos_chapter');
		$this->dropIndex('domain', 'import_fos_chapter');
		$this->dropIndex('chapter_id', 'import_fos_chapter');
		$this->renameColumn('import_fos_chapter', 'id', 'pkey');
		$this->renameColumn('import_fos_chapter', 'chapter_id', 'id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m190123_133810_refactor_chapter cannot be reverted.\n";

		return false;
	}
	*/
}
