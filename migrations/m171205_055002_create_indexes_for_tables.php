<?php

use yii\db\Migration;

/**
 * Class m171205_055002_create_indexes_for_tables
 */
class m171205_055002_create_indexes_for_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->renameColumn('block', 'key', 'blockKey');
        $this->renameColumn('block', 'value', 'blockValue');

        $this->createIndex('product-attribute-title', 'product_attribute', 'title');
        $this->createIndex('product-attribute-type', 'product_attribute', 'type');

        $this->createIndex('product-attribute-group-type', 'product_attribute_group', 'title');

        $this->createIndex('product-to-attribute-product', 'product_to_attribute', 'productId');
        $this->createIndex('product-to-attribute-attribute', 'product_to_attribute', 'attributeId');

//        $this->createIndex('product-cost', 'product', 'cost');

        $this->createIndex('product-price-product', 'product_price', 'productId');
        $this->createIndex('product-price-product-group', 'product_price', 'productPriceGroupId');
        $this->createIndex('product-price-product-value', 'product_price', 'value');

        $this->createIndex('client-title', 'client', 'title');
        $this->createIndex('client-type', 'client', 'type');
        $this->createIndex('client-status', 'client', 'status');

        $this->createIndex('product-create', 'product', 'dtCreate');
        $this->createIndex('product-update', 'product', 'dtUpdate');

        $this->createIndex('post-create', 'post', 'dtCreate');
        $this->createIndex('post-update', 'post', 'dtUpdate');

        $this->createIndex('page-create', 'page', 'dtCreate');
        $this->createIndex('page-update', 'page', 'dtUpdate');


        $this->createIndex('user-to-client-user', 'user_to_client', 'userId');
        $this->createIndex('user-to-client-client', 'user_to_client', 'clientId');
        $this->createIndex('user-to-client-client-user', 'user_to_client', ['clientId', 'userId']);


        $this->createIndex('orders-client', 'order', 'clientId');
        $this->createIndex('orders-user', 'order', 'userId');
        $this->createIndex('orders-client-user', 'order', ['clientId', 'userId']);

        $this->createIndex('address-country', 'address', 'country');
        $this->createIndex('address-city', 'address', 'city');
        $this->createIndex('address-region', 'address', 'region');
        $this->createIndex('address-full', 'address', ['country', 'city', 'region']);

        $this->createIndex('product-analogue-product', 'product_analogue', 'productId');
        $this->createIndex('product-analogue-product-analogue', 'product_analogue', 'productAnalogueId');
        $this->createIndex('product-analogue-backcomp', 'product_analogue', 'backcomp');

        $this->createIndex('block-title', 'block', 'title');
        $this->createIndex('block-key', 'block', 'blockKey');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('block', 'blockKey', 'key');
        $this->renameColumn('block', 'blockValue', 'value');

        $this->dropIndex('product-attribute-title', 'product_attribute');
        $this->dropIndex('product-attribute-type', 'product_attribute');

        $this->dropIndex('product-attribute-group-type', 'product_attribute_group');

        $this->dropIndex('product-to-attribute-product', 'product_to_attribute');
        $this->dropIndex('product-to-attribute-attribute', 'product_to_attribute');

        $this->dropIndex('product-price-product', 'product_price');
        $this->dropIndex('product-price-product-group', 'product_price');
        $this->dropIndex('product-price-product-value', 'product_price');

        $this->dropIndex('client-title', 'client');
        $this->dropIndex('client-type', 'client');
        $this->dropIndex('client-status', 'client');

        $this->dropIndex('product-create', 'product');
        $this->dropIndex('product-update', 'product');

        $this->dropIndex('post-create', 'post');
        $this->dropIndex('post-update', 'post');

        $this->dropIndex('page-create', 'page');
        $this->dropIndex('page-update', 'page');


        $this->dropIndex('user-to-client-user', 'user_to_client');
        $this->dropIndex('user-to-client-client', 'user_to_client');
        $this->dropIndex('user-to-client-client-user', 'user_to_client');


        $this->dropIndex('orders-client', 'order');
        $this->dropIndex('orders-user', 'order');
        $this->dropIndex('orders-client-user', 'order');

        $this->dropIndex('address-country', 'address');
        $this->dropIndex('address-city', 'address');
        $this->dropIndex('address-region', 'address');
        $this->dropIndex('address-full', 'address');

        $this->dropIndex('product-analogue-product', 'product_analogue');
        $this->dropIndex('product-analogue-product-analogue', 'product_analogue');
        $this->dropIndex('product-analogue-backcomp', 'product_analogue');

        $this->dropIndex('block-title', 'block');
        $this->dropIndex('block-key', 'block');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo 'm171205_055002_create_indexes_for_tables cannot be reverted.\n';

        return false;
    }
    */
}
