<?php

use yii\db\Migration;

/**
 * Class m190123_140108_refactor_command
 */
class m190123_140108_refactor_command extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->renameColumn('import_fos_command', 'id', 'command_id');
		$this->renameColumn('import_fos_command', 'pkey', 'id');
		$this->createIndex('command_id', 'import_fos_command', 'command_id', true);
		$this->createIndex('domain', 'import_fos_command', 'domain');
		$this->createIndex('owner_id', 'import_fos_command', 'owner_id');
		$this->createIndex('cluster_id', 'import_fos_command', 'cluster_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropIndex('cluster_id', 'import_fos_command');
		$this->dropIndex('owner_id', 'import_fos_command');
		$this->dropIndex('domain', 'import_fos_command');
		$this->dropIndex('command_id', 'import_fos_command');
		$this->renameColumn('import_fos_command', 'id', 'pkey');
		$this->renameColumn('import_fos_command', 'command_id', 'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190123_140108_refactor_command cannot be reverted.\n";

        return false;
    }
    */
}
