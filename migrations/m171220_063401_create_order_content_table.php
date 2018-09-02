<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_content`.
 */
class m171220_063401_create_order_content_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_content', [
            'id' => $this->primaryKey(),
            'orderId' => $this->integer()->comment('Заказ'),
            'productId' => $this->integer()->comment('Продукт'),
            'priceValue' => $this->integer()->comment('Цена'),
            'count' => $this->integer()->comment('Количество')
        ]);

        $this->createIndex('order-content-order', 'order_content', 'orderId');
        $this->createIndex('order-content-product', 'order_content', 'productId');
        $this->createIndex('order-content-price', 'order_content', 'priceValue');
        $this->createIndex('order-content-count', 'order_content', 'count');

        $this->dropColumn('order', 'products');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_content');
    }
}
