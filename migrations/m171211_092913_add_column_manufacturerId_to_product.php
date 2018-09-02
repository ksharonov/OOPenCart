<?php

use yii\db\Migration;

/**
 * Class m171211_092913_add_column_manufacturerId_to_product
 */
class m171211_092913_add_column_manufacturerId_to_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'manufacturerId', $this->integer());
        $this->createIndex('product-manufacturer', 'product', 'manufacturerId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'manufacturerId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171211_092913_add_column_manufacturerId_to_product cannot be reverted.\n";

        return false;
    }
    */
}
