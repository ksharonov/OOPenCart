<?php

use yii\db\Migration;

/**
 * Class m180413_053631_add_fulltext_index_to_product
 */
class m180413_053631_add_fulltext_index_to_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE product ADD FULLTEXT(title)");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE product DROP INDEX title");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180413_053631_add_fulltext_index_to_product cannot be reverted.\n";

        return false;
    }
    */
}
