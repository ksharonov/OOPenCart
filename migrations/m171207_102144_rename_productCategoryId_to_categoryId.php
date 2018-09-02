<?php

use yii\db\Migration;

/**
 * Class m171207_102144_rename_productCategoryId_to_categoryId
 */
class m171207_102144_rename_productCategoryId_to_categoryId extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('product_filter_to_category', 'productCategoryId', 'categoryId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('product_filter_to_category', 'categoryId', 'productCategoryId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171207_102144_rename_productCategoryId_to_categoryId cannot be reverted.\n";

        return false;
    }
    */
}
