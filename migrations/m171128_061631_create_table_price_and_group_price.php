<?php

use yii\db\Migration;

/**
 * Class m171128_061631_create_table_price_and_group_price
 */
class m171128_061631_create_table_price_and_group_price extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_price', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->comment('id продукта'),
            'productPriceGroupId' => $this->integer()->comment('id группы цен'),
            'value' => $this->integer()->comment('Цена'),
            'dtStart' => $this->dateTime()->comment('Дата начала'),
            'dtEnd' => $this->dateTime()->comment('Дата окончания')
        ]);

        $this->createTable('product_price_group', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_price');
        $this->dropTable('product_price_group');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_061631_create_table_price_and_group_price cannot be reverted.\n";

        return false;
    }
    */
}
