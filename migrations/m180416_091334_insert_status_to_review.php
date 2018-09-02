<?php

use yii\db\Migration;

/**
 * Class m180416_091334_insert_status_to_review
 */
class m180416_091334_insert_status_to_review extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_review', 'status', $this->integer()->comment('Статус'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_review', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180416_091334_insert_status_to_review cannot be reverted.\n";

        return false;
    }
    */
}
