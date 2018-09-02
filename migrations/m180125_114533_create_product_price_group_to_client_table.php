<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_price_group_to_client`.
 */
class m180125_114533_create_product_price_group_to_client_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('product_price_group_to_client', [
            'id' => $this->primaryKey(),
            'productPriceGroupId' => $this->integer()->comment('Прайс'),
            'clientId' => $this->integer()->comment('Клиент')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('product_price_group_to_client');
    }
}
