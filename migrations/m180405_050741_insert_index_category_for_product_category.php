<?php

use yii\db\Migration;

/**
 * Class m180405_050741_insert_index_category_for_product_category
 */
class m180405_050741_insert_index_category_for_product_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('product_category', ['title' => 'На главной', 'parentId' => '-10']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180405_050741_insert_index_category_for_product_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_050741_insert_index_category_for_product_category cannot be reverted.\n";

        return false;
    }
    */
}
