<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_associated`.
 */
class m171218_044321_create_product_associated_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('product_associated', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->comment('Продукт'),
            'productAssociatedId' => $this->integer()->comment('Связанный продукт')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('product_associated');
    }
}
