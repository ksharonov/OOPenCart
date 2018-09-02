<?php

use yii\db\Migration;

/**
 * Class m171221_065031_add_small_content_etc_to_product
 */
class m171221_065031_add_small_content_etc_to_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'smallDescription', $this->string(70)->comment('Краткое описание'));
        $this->addColumn('product', 'vendorCode', $this->string(64)->comment('Артикул'));
        $this->addColumn('manufacturer', 'vendorCode', $this->string(64)->comment('Артикул'));

        $this->createIndex('manufacturer-vendor-code', 'manufacturer', 'vendorCode');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'smallDescription');
        $this->dropColumn('product', 'vendorCode');
        $this->dropColumn('manufacturer', 'vendorCode');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171221_065031_add_small_content_etc_to_product cannot be reverted.\n";

        return false;
    }
    */
}
