<?php

use yii\db\Migration;

/**
 * Class m180427_041350_add_idx_to_tables
 */
class m180427_041350_add_idx_to_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (\Yii::$app->db->getTableSchema('{{%delivery_condition}}', true) !== null) {
            $this->dropTable('delivery_condition');
        }

//        @$this->dropIndexes();
//
//        $this->createIndex('idx-block-type', 'block', 'type');
//        $this->createIndex('idx-city-on-city-city', 'city_on_site', 'cityId');
//        $this->createIndex('idx-country-title', 'country', 'title');
//        $this->createIndex('idx-relModel-discount', 'discount', 'relModel');
//        $this->createIndex('idx-relModelId-discount', 'discount', 'relModelId');
//        $this->createIndex('idx-relModelFull-discount', 'discount', 'relModel, relModelId');
//        $this->createIndex('idx-type-discount', 'discount', 'type');
//        $this->createIndex('idx-priority-discount', 'discount', 'priority');
//        $this->createIndex('idx-name-extension', 'extension', 'name');
//        $this->createIndex('idx-class-extension', 'extension', 'class');
//        $this->createIndex('idx-type-extension', 'extension', 'type');
//        $this->createIndex('idx-status-extension', 'extension', 'status');
//        $this->createIndex('idx-type-status-extension', 'extension', 'type, status');
//        $this->createIndex('idx-parent-extension', 'extension', 'parentId');
//        $this->createIndex('idx-orderId-extension', 'lexema_order', 'orderId');
//        $this->createIndex('idx-orderNumber-extension', 'lexema_order', 'orderNumber');
//        $this->createIndex('idx-slug-manufacturer', 'manufacturer', 'slug');
//        $this->createIndex('idx-deliveryMethod-order', 'order', 'deliveryMethod');
//        $this->createIndex('idx-address-order', 'order', 'addressId');
//        $this->createIndex('idx-discountValue-order-content', 'order_content', 'discountValue');
//        $this->createIndex('idx-slug-page', 'page', 'slug');
//        $this->createIndex('idx-slug-post', 'post', 'slug');
//        $this->createIndex('idx-slug-product', 'product', 'slug');
//        $this->createIndex('idx-slug-post-category', 'post_category', 'slug');
//
//        $this->createIndex('idx-type-storage', 'storage', 'type');
//        $this->createIndex('idx-status-storage', 'storage', 'status');
//        $this->createIndex('idx-city-storage', 'storage', 'cityId');
//        $this->createIndex('idx-parent-storage', 'storage', 'parentId');
//        $this->createIndex('idx-name-template', 'template', 'name');
////        $this->createIndex('idx-status-storage', 'storage', 'status');
//        $this->createIndex('idx-username-user', 'user', 'username');
//        $this->createIndex('idx-email-user', 'user', 'email');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
//        $this->dropIndexes();
    }

    public function dropIndexes()
    {
        //        $this->dropTable('delivery_condition');

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `block` DROP INDEX `idx-block-type`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `city_on_site` DROP INDEX `idx-city-on-city-city`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `country` DROP INDEX `idx-country-title`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `discount` DROP INDEX `idx-relModel-discount`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `discount` DROP INDEX `idx-relModelId-discount`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `discount` DROP INDEX `idx-relModelFull-discount`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `discount` DROP INDEX `idx-type-discount`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `discount` DROP INDEX `idx-priority-discount`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `extension` DROP INDEX `idx-name-extension`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `extension` DROP INDEX `idx-class-extension`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `extension` DROP INDEX `idx-type-extension`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }
        try {
            @Yii::$app->db->createCommand('ALTER TABLE `extension` DROP INDEX `idx-status-extension`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `extension` DROP INDEX `idx-type-status-extension`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `extension` DROP INDEX `idx-parent-extension`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `lexema_order` DROP INDEX `idx-orderId-extension`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `lexema_order` DROP INDEX `idx-orderNumber-extension`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `manufacturer` DROP INDEX `idx-slug-manufacturer`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `order` DROP INDEX `idx-deliveryMethod-order`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `order` DROP INDEX `idx-address-order`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `order_content` DROP INDEX `idx-discountValue-order-content`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `page` DROP INDEX `idx-slug-page`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `post` DROP INDEX `idx-slug-post`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `product` DROP INDEX `idx-slug-product`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }


        try {
            @Yii::$app->db->createCommand('ALTER TABLE `post_category` DROP INDEX `idx-slug-post-category`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `region` DROP INDEX `idx-title-region`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `storage` DROP INDEX `idx-type-storage`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `storage` DROP INDEX `idx-status-storage`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `storage` DROP INDEX `idx-city-storage`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `storage` DROP INDEX `idx-parent-storage`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `template` DROP INDEX `idx-name-template`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `user` DROP INDEX `idx-username-user`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }

        try {
            @Yii::$app->db->createCommand('ALTER TABLE `user` DROP INDEX `idx-email-user`')->execute();
        } catch (Exception $exception) {
            echo "deleted";
        }


//        $this->dropIndex('idx-status-storage', 'storage');


    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180427_041350_add_idx_to_tables cannot be reverted.\n";

        return false;
    }
    */
}
