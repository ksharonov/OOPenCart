<?php

use yii\db\Migration;

/**
 * Class m180215_043452_add_thumbnail_to_product_category
 */
class m180215_043452_add_thumbnail_to_product_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_category', 'thumbnail', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_category', 'thumbnail');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180215_043452_add_thumbnail_to_product_category cannot be reverted.\n";

        return false;
    }
    */
}
