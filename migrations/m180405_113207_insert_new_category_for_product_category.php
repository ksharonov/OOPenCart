<?php

use yii\db\Migration;

/**
 * Class m180405_113207_insert_new_category_for_product_category
 */
class m180405_113207_insert_new_category_for_product_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('product_category', ['title' => 'Новинки', 'parentId' => '-20']);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180405_113207_insert_new_category_for_product_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_113207_insert_new_category_for_product_category cannot be reverted.\n";

        return false;
    }
    */
}
