<?php

use yii\db\Migration;

/**
 * Class m180125_100242_add_column_type_to_post
 */
class m180125_100242_add_column_type_to_post extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('post', 'type', $this->integer()->comment('Тип поста'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('post', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180125_100242_add_column_type_to_post cannot be reverted.\n";

        return false;
    }
    */
}
