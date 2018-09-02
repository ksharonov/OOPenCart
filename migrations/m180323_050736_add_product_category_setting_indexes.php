<?php

use yii\db\Migration;

/**
 * Class m180323_050736_add_product_category_setting_indexes
 */
class m180323_050736_add_product_category_setting_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('parentId_idx', 'product_category', 'parentId');
        $this->createIndex('setKey_idx', 'setting', 'setKey');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('parentId_idx', 'product_category');
        $this->dropIndex('setKey_idx', 'setting');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180323_050736_add_product_category_setting_indexes cannot be reverted.\n";

        return false;
    }
    */
}
