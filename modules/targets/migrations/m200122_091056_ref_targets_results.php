<?php

use yii\db\Migration;

/**
 * Class m200122_091056_ref_targets_results
 */
class m200122_091056_ref_targets_results extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('ref_targets_results', [
			'id' => $this->primaryKey(),
			'name' => $this->string(256)->notNull()->comment('Название'),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ref_targets_results');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200122_091056_ref_targets_results cannot be reverted.\n";

        return false;
    }
    */
}
