<?php

use yii\db\Migration;

/**
 * Class m190422_095411_ref_vacancy_recruiters
 */
class m190422_095411_ref_vacancy_recruiters extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('ref_vacancy_recruiters',[
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'color' => $this->string(255)->null(),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropTable('ref_vacancy_recruiters');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190422_095411_ref_vacancy_recruiters cannot be reverted.\n";

        return false;
    }
    */
}
